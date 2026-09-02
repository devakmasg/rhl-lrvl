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

    php deploy/make-update.php          # build update-<date>.zip
    # upload + extract at ~/domains/rhlproperties.com/, overwrite
    php deploy/make-update.php --done   # record the deploy

The script diffs the project against `deployed-manifest.json`, ships only changed
files already arranged as `laravel/` + `public_html/`, and warns when a migration
or `composer.lock` changed. `--done` only after a successful upload.

- **New migration:** the upload does not run it. Needs `php artisan migrate --force`
  over SSH, or the equivalent SQL in phpMyAdmin.
- **composer.lock changed:** run `composer install --no-dev -o` locally and upload
  the whole `vendor/`, or the site fatals.
- No config caching is active on the server, so `.env` edits take effect at once.
  If `php artisan config:cache` is ever run there, every later `.env` edit needs it
  re-run.

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
("push this live", "upload my changes"). Run the whole thing for them and hand
back one zip plus a one-line instruction. Do not make them read the steps.

1. `php deploy/make-update.php` — read the output.
2. Report what changed in plain language, then give them the zip path and:
   "upload to ~/domains/rhlproperties.com/ and Extract, overwrite when asked."
3. After they confirm the upload worked:
   `php deploy/make-update.php --done`
   Never run `--done` before they confirm — it desyncs every future diff.

**If the script reports a new/changed migration**, the upload does not apply it.
Generate the SQL for them rather than asking them to run artisan:

    php artisan migrate --pretend

That prints the exact statements. Hand over the ones belonging to the new
migration, to paste into phpMyAdmin -> SQL tab. Then insert the migration's
filename into the `migrations` table so Laravel does not try it again:

    INSERT INTO migrations (migration, batch)
    VALUES ('2026_09_02_000000_example', (SELECT * FROM (SELECT MAX(batch)+1 FROM migrations) x));

(If SSH is ever set up, `php artisan migrate --force` on the server replaces all
of this.)

**If the script reports composer.lock changed**, run
`composer install --no-dev --optimize-autoloader` in a staging copy, zip the
resulting `vendor/` as `laravel/vendor/`, and give them that as a second upload.
Never ship the local dev `vendor/`.

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
