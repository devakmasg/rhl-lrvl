<?php
/**
 * Copy this file to deploy/ssh-config.local.php and fill in real values.
 * That copy is gitignored — never commit server credentials.
 *
 * host/port/user come from hPanel → Advanced → SSH Access.
 * key is the private half of a key pair whose public half was added under
 * "Manage SSH Keys" in that same panel (see deploy/ssh-deploy.php header).
 * remoteBase is the folder that contains laravel/ and public_html/, relative
 * to the SSH user's home directory.
 */

return [
    'host'       => '203.0.113.10',
    'port'       => 65002,
    'user'       => 'uXXXXXXXXX',
    'key'        => str_replace('\\', '/', getenv('USERPROFILE') ?: ($_SERVER['HOME'] ?? '')).'/.ssh/rhl_hostinger',
    'remoteBase' => 'domains/rhlproperties.com',
];
