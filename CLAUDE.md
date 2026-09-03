# RHL Properties (hsc-app)

Laravel 13 / PHP 8.3, MySQL. Public marketing site plus an admin CMS where the
client edits every page, image and project. Frontend CSS/JS are the hand-written
files under `public/assets` — the Vite build exists but the site does not use it.

## The site is LIVE

Production: **https://rhlproperties.com** on Hostinger shared hosting, deployed
2026-09-01. Server layout, under `~/domains/rhlproperties.com/`:

    laravel/        the app, outside the web root
    public_html/    the web root; its index.php requires ../laravel and calls
                    $app->usePublicPath(__DIR__)

Because of that `usePublicPath` call, `public_path()` on the server means
`public_html`, which is what makes `asset()` and the public disk resolve correctly.

## Uploaded images: no symlink

Hostinger disables `symlink()` in the web SAPI, so `php artisan storage:link` and
any symlink helper are dead ends. Instead `config/filesystems.php` sets the public
disk root to `public_path('storage')`:

- **Server:** uploads live in `public_html/storage/` (a real folder). The admin
  writes new uploads straight into the web root.
- **Local:** `public/storage` is the usual symlink to `storage/app/public`, so the
  same config resolves to the same files. Nothing to change when working locally.

## Deploying a change

SSH access is set up (added 2026-09-04) — this is now the normal path:

    php deploy/ssh-deploy.php

One command. It diffs the project against `deployed-manifest.json`, tars only the
changed files straight into `~/domains/rhlproperties.com/` over SSH, deletes
anything removed locally, runs `composer install --no-dev -o` on the server if
`composer.lock` changed, runs `php artisan migrate --force` on the server if a
migration changed, and — only if all of that succeeds — records the deploy
itself. No manual upload, no separate `--done` step. A non-zero exit means
nothing (or only part of it) landed; the message says which step failed.

Connection details live in `deploy/ssh-config.local.php` (gitignored — never
commit it); `deploy/ssh-config.example.php` is the template. The private key is
`~/.ssh/rhl_hostinger`, whose public half is added under hPanel → Advanced → SSH
Access → Manage SSH Keys. A new machine needs its own key pair generated and
added there, plus its own `ssh-config.local.php` copy.

No config caching is active on the server, so `.env` edits take effect at once.
If `php artisan config:cache` is ever run there, every later `.env` edit needs it
re-run.

### Fallback: no SSH available

If SSH ever breaks (key revoked, host changed), `deploy/make-update.php` still
works — same manifest, same file selection, but produces something to upload by
hand instead of pushing directly:

    php deploy/make-update.php          # build update-<date>.zip
    # upload + extract at ~/domains/rhlproperties.com/, overwrite
    php deploy/make-update.php --done   # record the deploy

It also emits a one-click PHP installer (`deploy/install-<date>.php`) as an
easier alternative to the zip — upload that single file to `public_html`, open
it once in a browser with its `?key=`, and it writes every changed file to its
real path then deletes itself. Either way, without SSH a new/changed migration
needs its SQL applied by hand in phpMyAdmin (see the section below), and a
changed `composer.lock` needs `vendor/` built locally and uploaded whole.

## Never do these

1. **Never re-import `rhl_database.sql`.** It is a pre-launch snapshot. The live
   database is the only current copy of the client's content.
2. **Never overwrite or delete `public_html/storage/`** (server) — every image the
   client uploads lives there and nowhere else. It is not in git.
3. **Never upload the local `.env`.** It holds local database credentials.
4. **Never set `APP_DEBUG=true` on production** to read an error. Read
   `laravel/storage/logs/laravel-*.log` instead.

To work against real content, export the live database from phpMyAdmin into the
local database — never the other direction.

## Local development

    php artisan serve        # or the Laragon vhost at http://hsc-app.test
    npm run dev

MySQL runs under Laragon (`C:\laragon`); it is not a Windows service, so start
Laragon before artisan commands that touch the database.

## Deploying on request — the assistant does this, not the user

The user develops; they do not memorise the deploy procedure and will simply ask
("push this live", "upload my changes"). Run it for them — with SSH set up this
is now a single command and needs no confirmation round-trip:

1. `php deploy/ssh-deploy.php` — read the output.
2. Report what changed and whether it deployed clean, in plain language. If a
   migration or `composer.lock` was involved, say so explicitly since those run
   automatically now — worth a sentence even though nothing failed.
3. If it fails partway (upload ok but migration failed, etc.), the output says
   which step — fix the underlying issue and re-run rather than retrying blindly;
   re-running is safe since the diff is idempotent.

### If SSH is unavailable, fall back to the zip/installer flow

Use `php deploy/make-update.php` instead (see the fallback section above) and
hand back the zip or one-click installer plus a one-line instruction — do not
make the user read multi-step instructions. After they confirm the upload
worked, run `php deploy/make-update.php --done`. Never run `--done` before they
confirm — it desyncs every future diff.

Without SSH, a reported migration needs its SQL generated by hand instead of
applied automatically:

    php artisan migrate --pretend

That prints the exact statements. Hand over the ones belonging to the new
migration, to paste into phpMyAdmin -> SQL tab. Then insert the migration's
filename into the `migrations` table so Laravel does not try it again:

    INSERT INTO migrations (migration, batch)
    VALUES ('2026_09_02_000000_example', (SELECT * FROM (SELECT MAX(batch)+1 FROM migrations) x));

And a changed `composer.lock` needs `composer install --no-dev --optimize-autoloader`
run in a staging copy, with the resulting `vendor/` zipped as `laravel/vendor/`
and handed over as a second upload. Never ship the local dev `vendor/`.

Always verify before handing anything over: the zip must contain no `.env`, no
`storage/`, and no `public_html/storage/`.

## Working on another machine

`git clone https://github.com/devakmasg/rhl-lrvl.git` brings the code, this file
and `deploy/` (script + `deployed-manifest.json`), so the release procedure works
anywhere. Always commit `deploy/deployed-manifest.json` after a deploy, or the
next machine rebuilds a package containing files that are already live.

What does NOT come from git, and has to exist on the new machine:

- `.env` — copy from another machine or rebuild from `.env.example`; local DB
  credentials, and `APP_KEY` must match nothing in particular locally.
- The local database — export the **live** database from phpMyAdmin and import it
  locally. Never seed over live, and never import an old local dump into live.
- `vendor/` and `node_modules/` — `composer install` and `npm install`.
- `public/storage` symlink — `php artisan storage:link` (works locally; only
  Hostinger's web PHP blocks it).
- `storage/app/public` uploads — locally these are older than production. Pull
  images down from the server if you need them; treat the server as the source.
- SSH deploy access — generate a new key pair (`ssh-keygen -t ed25519`), add the
  public half under hPanel → Advanced → SSH Access → Manage SSH Keys, then copy
  `deploy/ssh-config.example.php` to `deploy/ssh-config.local.php` and point
  `key` at the new private key. Until that's done, use the zip/installer fallback.
