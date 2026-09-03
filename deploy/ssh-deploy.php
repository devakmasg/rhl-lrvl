<?php
/**
 * Deploys straight to Hostinger over SSH — no zip, no browser step.
 *
 *   php deploy/ssh-deploy.php
 *
 * Diffs the project against the last recorded deploy (the same manifest
 * make-update.php uses — the two tools are interchangeable), tars the
 * changed files straight into the server, deletes anything removed
 * locally, runs composer install / migrate remotely when needed, then
 * records the deploy itself (no separate --done step, since a non-zero
 * exit here means nothing landed).
 *
 * Needs deploy/ssh-config.local.php — copy ssh-config.example.php and fill
 * in your own connection details. That file is gitignored: never commit
 * server credentials. The private key it points at must correspond to a
 * public key added under hPanel → Advanced → SSH Access → Manage SSH Keys.
 *
 * Never ships .env, laravel/storage/, or public_html/storage/ — see
 * common.php and CLAUDE.md for why.
 */

require __DIR__.'/common.php';

$here     = __DIR__;
$project  = dirname($here);
$manifest = $here.'/deployed-manifest.json';

$configFile = $here.'/ssh-config.local.php';
if (! is_file($configFile)) {
    echo "Missing deploy/ssh-config.local.php — copy deploy/ssh-config.example.php and fill it in.\n";
    exit(1);
}
$config = require $configFile;
$host = $config['host'];
$port = (string) $config['port'];
$user = $config['user'];
$key  = $config['key'];
$remoteBase = $config['remoteBase'];

if (! is_file($key)) {
    echo "SSH private key not found at $key\n";
    echo "Generate one and add the public half in hPanel — see ssh-deploy.php's header comment.\n";
    exit(1);
}

function run(array $cmd): int {
    echo '$ '.implode(' ', array_map(fn ($c) => str_contains($c, ' ') ? "\"$c\"" : $c, $cmd))."\n";
    $process = proc_open($cmd, [1 => STDOUT, 2 => STDERR], $pipes);
    if (! is_resource($process)) { return 1; }
    return proc_close($process);
}

function sshCmd(array $config, string $remoteCommand): array {
    return ['ssh', '-p', (string) $config['port'], '-i', $config['key'], '-o', 'BatchMode=yes',
             $config['user'].'@'.$config['host'], $remoteCommand];
}

$current = deployCollect($project);
[$previous, $changed, $removed] = deployDiff($manifest, $current);

if ($previous === null) {
    echo "No deploy recorded yet. Run: php deploy/make-update.php --done\n";
    echo "(that marks the current code as what is live, so future runs can diff against it)\n";
    exit(1);
}
if (! $changed && ! $removed) { echo "Nothing changed since the last deploy.\n"; exit; }

// Stage changed files under the server's folder names.
$stage = $here.'/.stage';
if (is_dir($stage)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($stage, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($it as $f) { $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname()); }
    rmdir($stage);
}
mkdir($stage, 0777, true);
foreach ($changed as $rel) {
    $target = $stage.'/'.deployRemotePath($rel);
    if (! is_dir(dirname($target))) { mkdir(dirname($target), 0777, true); }
    copy($project.'/'.$rel, $target);
}

echo count($changed)." changed file(s):\n";
foreach ($changed as $rel) { echo "  $rel\n"; }
if ($removed) {
    echo count($removed)." file(s) removed locally:\n";
    foreach ($removed as $rel) { echo "  ".deployRemotePath($rel)."\n"; }
}

// Archive the staged files and push them over in one shot.
$tarExe  = is_file('C:/Windows/System32/tar.exe') ? 'C:/Windows/System32/tar.exe' : 'tar';
$archive = $stage.'.tar.gz';
$parts   = array_values(array_filter(['laravel', 'public_html'], fn ($d) => is_dir($stage.'/'.$d)));

echo "\nPackaging...\n";
if (run([$tarExe, '-C', $stage, '-czf', $archive, ...$parts]) !== 0) {
    echo "\ntar failed — nothing uploaded.\n";
    exit(1);
}

echo "\nUploading...\n";
$remoteArchive = $remoteBase.'/update.tar.gz';
if (run(['scp', '-P', $port, '-i', $key, '-o', 'BatchMode=yes', $archive, "$user@$host:$remoteArchive"]) !== 0) {
    echo "\nUpload failed — server untouched.\n";
    exit(1);
}
unlink($archive);

echo "\nExtracting on the server...\n";
if (run(sshCmd($config, "cd $remoteBase && tar -xzf update.tar.gz && rm -f update.tar.gz")) !== 0) {
    echo "\nExtraction failed on the server — check by hand, it may be partially applied.\n";
    exit(1);
}

if ($removed) {
    echo "\nRemoving deleted file(s) on the server...\n";
    $quoted = implode(' ', array_map(fn ($r) => "'".$remoteBase.'/'.deployRemotePath($r)."'", $removed));
    run(sshCmd($config, "rm -f $quoted"));
}

$migrations = array_filter($changed, fn ($f) => str_starts_with($f, 'database/migrations/'));
if ($migrations) {
    echo "\n".count($migrations)." new/changed migration(s) — applying on the server:\n";
    foreach ($migrations as $m) { echo "  $m\n"; }
    if (run(sshCmd($config, "cd $remoteBase/laravel && php artisan migrate --force")) !== 0) {
        echo "\nMigration failed — check laravel/storage/logs on the server before trusting the site.\n";
        exit(1);
    }
}

if (in_array('composer.lock', $changed, true)) {
    echo "\ncomposer.lock changed — installing dependencies on the server:\n";
    if (run(sshCmd($config, "cd $remoteBase/laravel && composer install --no-dev --optimize-autoloader")) !== 0) {
        echo "\ncomposer install failed on the server — site may be broken until this is resolved.\n";
        exit(1);
    }
}

file_put_contents($manifest, json_encode($current, JSON_PRETTY_PRINT));
echo "\nDeployed and recorded ".count($current)." files as live.\n";
echo "Commit deploy/deployed-manifest.json so other machines stay in sync.\n";
