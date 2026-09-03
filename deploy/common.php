<?php
/**
 * Shared by make-update.php and ssh-deploy.php: what ships, what never ships,
 * and how to hash the project against the last recorded deploy.
 */

function deployAppPaths(): array {
    return ['app', 'bootstrap/app.php', 'config', 'database', 'resources', 'routes', 'artisan', 'composer.json', 'composer.lock'];
}

function deployWebPaths(): array {
    return ['public/assets', 'public/build', 'public/favicon.ico', 'public/robots.txt', 'public/.htaccess'];
}

function deploySkip(): array {
    return ['/vendor/', '/node_modules/', '/storage/', '/.git/', '/bootstrap/cache/',
            '/public/storage/', '/public/hot', '/public/index.php', '/.env', '/database/database.sqlite'];
}

function deployCollect(string $project): array {
    $out = [];
    foreach (array_merge(deployAppPaths(), deployWebPaths()) as $p) {
        $full = $project.'/'.$p;
        if (is_file($full)) { $out[$p] = md5_file($full); continue; }
        if (! is_dir($full)) { continue; }
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($full, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if (! $file->isFile()) { continue; }
            $rel = str_replace(DIRECTORY_SEPARATOR, '/', substr($file->getPathname(), strlen($project) + 1));
            foreach (deploySkip() as $s) { if (str_contains('/'.$rel, $s)) { continue 2; } }
            $out[$rel] = md5_file($file->getPathname());
        }
    }
    ksort($out);
    return $out;
}

// Where a project-relative path lands on the server, under ~/domains/rhlproperties.com/.
function deployRemotePath(string $rel): string {
    return str_starts_with($rel, 'public/') ? 'public_html/'.substr($rel, 7) : 'laravel/'.$rel;
}

// Loads the last-recorded-deploy manifest and diffs $current against it.
// Returns [$previous, $changed, $removed].
function deployDiff(string $manifestPath, array $current): array {
    $previous = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
    if ($previous === null) { return [null, [], []]; }

    $changed = [];
    foreach ($current as $rel => $hash) {
        if (! isset($previous[$rel]) || $previous[$rel] !== $hash) { $changed[] = $rel; }
    }
    $removed = array_values(array_diff(array_keys($previous), array_keys($current)));

    return [$previous, $changed, $removed];
}
