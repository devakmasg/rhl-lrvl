<?php
/**
 * Builds an incremental update package for the live Hostinger site.
 *
 *   php deploy/make-update.php          build update-<date>.zip from everything
 *                                       changed since the last recorded deploy
 *   php deploy/make-update.php --done   record the current state as deployed
 *                                       (only AFTER a successful upload)
 *
 * The zip mirrors the server layout, so extracting it at
 * ~/domains/rhlproperties.com/ drops every file exactly where it belongs.
 *
 * Lives inside the repo on purpose: clone the project on any machine and the
 * deploy procedure comes with it. See CLAUDE.md for the full agreement.
 */

require __DIR__.'/common.php';

$here     = __DIR__;
$project  = dirname($here);
$manifest = $here.'/deployed-manifest.json';

// Windows ships bsdtar, which can write zips; fall back to whatever is on PATH.
$tarExe = is_file('C:/Windows/System32/tar.exe') ? 'C:/Windows/System32/tar.exe' : 'tar';

$current = deployCollect($project);

if (in_array('--done', $argv, true)) {
    file_put_contents($manifest, json_encode($current, JSON_PRETTY_PRINT));
    echo "Recorded ".count($current)." files as deployed.\n";
    echo "Commit deploy/deployed-manifest.json so other machines stay in sync.\n";
    exit;
}

[$previous, $changed, $removed] = deployDiff($manifest, $current);

if ($previous === null) {
    echo "No deploy recorded yet. Run: php deploy/make-update.php --done\n";
    echo "(that marks the current code as what is live, so future runs can diff against it)\n";
    exit(1);
}

if (! $changed && ! $removed) { echo "Nothing changed since the last deploy.\n"; exit; }

// Stage the changed files under the server's folder names.
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

$zip = $here.'/update-'.date('Y-m-d-Hi').'.zip';
$parts = array_values(array_filter(['laravel', 'public_html'], fn ($d) => is_dir($stage.'/'.$d)));
$cmd = escapeshellarg($tarExe).' -a -c -f '.escapeshellarg($zip).' '.implode(' ', $parts);
$old = getcwd();
chdir($stage);
exec($cmd, $o, $code);
chdir($old);

echo "\n".count($changed)." changed file(s):\n";
foreach ($changed as $rel) { echo "  $rel\n"; }

if ($removed) {
    echo "\n".count($removed)." file(s) deleted locally — remove these on the server by hand:\n";
    foreach ($removed as $rel) { echo "  ".deployRemotePath($rel)."\n"; }
}

$migrations = array_filter($changed, fn ($f) => str_starts_with($f, 'database/migrations/'));
if ($migrations) {
    echo "\n!! ".count($migrations)." new/changed migration(s). The upload alone will NOT apply them.\n";
    echo "   Run on the server:  php artisan migrate --force\n";
    echo "   Without SSH, apply the equivalent SQL in phpMyAdmin instead.\n";
}

if (in_array('composer.lock', $changed, true)) {
    echo "\n!! composer.lock changed — dependencies differ. Run 'composer install --no-dev -o'\n";
    echo "   locally and upload the whole vendor/ folder, or the site will fatal.\n";
}

/* ---------- one-click installer ----------
   Hostinger's File Manager cannot extract a zip in place — its dialog demands a
   folder name, which nests everything a level deep and then needs the files
   moved by hand. So the same payload is also emitted as a single PHP file that
   is dropped into public_html and opened once in a browser: it writes each file
   to its real path, reports what it did, and deletes itself. */
$payload = [];
foreach ($changed as $rel) {
    $payload[deployRemotePath($rel)] = base64_encode(file_get_contents($project.'/'.$rel));
}

$key = bin2hex(random_bytes(8));
$installer = $here.'/install-'.date('Y-m-d-Hi').'.php';

$php = "<?php\n";
$php .= "/* RHL Properties update ".date('Y-m-d H:i')." — upload to public_html, open in a\n";
$php .= "   browser with ?key=".$key.", then it removes itself. Safe to re-run. */\n";
$php .= "if ((\$_GET['key'] ?? '') !== ".var_export($key, true).") { http_response_code(403); exit('Wrong or missing key.'); }\n";
$php .= "header('Content-Type: text/plain; charset=utf-8');\n";
$php .= "\$base = dirname(__DIR__);\n";
$php .= "if (! is_dir(\$base.'/laravel') || ! is_dir(\$base.'/public_html')) { exit(\"Not in public_html — expected laravel/ and public_html/ one level up.\n\"); }\n";
$php .= "\$files = ".var_export($payload, true).";\n";
$php .= "\$ok = 0; \$fail = 0;\n";
$php .= "foreach (\$files as \$rel => \$b64) {\n";
$php .= "    \$target = \$base.'/'.\$rel;\n";
$php .= "    if (! is_dir(dirname(\$target))) { @mkdir(dirname(\$target), 0755, true); }\n";
$php .= "    \$bytes = file_put_contents(\$target, base64_decode(\$b64));\n";
$php .= "    if (\$bytes === false) { \$fail++; echo \"FAILED  \$rel\n\"; } else { \$ok++; echo \"written \".str_pad(\$bytes, 7, ' ', STR_PAD_LEFT).\"  \$rel\n\"; }\n";
$php .= "}\n";
$php .= "echo \"\n\$ok file(s) written\".(\$fail ? \", \$fail FAILED\" : '').\".\n\";\n";
$php .= "if (! \$fail) { \$gone = @unlink(__FILE__); echo \$gone ? \"This installer has deleted itself.\n\" : \"Delete this file from public_html now.\n\"; }\n";

file_put_contents($installer, $php);

echo "\nOne-click installer (easier than the zip):\n";
echo "  $installer\n";
echo "  Upload it into public_html, then open:\n";
echo "  https://rhlproperties.com/".basename($installer)."?key=$key\n";
echo $code === 0
    ? "\nBuilt: $zip\nExtract it at ~/domains/rhlproperties.com/ (overwrite when asked).\nAfter a successful upload run: php deploy/make-update.php --done\n"
    : "\nZip failed (exit $code). Files are staged in $stage if you want to upload them by hand.\n";
