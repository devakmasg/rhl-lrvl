# RHL Properties — Hostinger deployment

Package contents (this is exactly the folder layout the server needs):

    laravel/            the application (must sit OUTSIDE the web root)
    public_html/        the web root — its index.php points at ../laravel
    rhl_database.sql    full export of the local hsc_app database (data included)
    DEPLOY.md           this file

Already done for you: production `composer install --no-dev --optimize-autoloader`,
`npm run build`, a fresh `APP_KEY`, and all admin-uploaded images
(`laravel/storage/app/public`, 12 MB) are inside the package.

Target: `~/domains/rhlproperties.com/` on Hostinger, so you end up with
`~/domains/rhlproperties.com/laravel` and `.../public_html`.

---

## 1. hPanel prep

1. The domain was bought from Hostinger, so DNS is already theirs — nothing to
   change at a registrar. In **Domains** the domain should read *Connected*, and
   under **Websites** it must be attached to this hosting plan. Attaching it is
   what creates `~/domains/rhlproperties.com/public_html`.
2. **Advanced → PHP Configuration → set PHP 8.3 or 8.4.** Laravel 13 refuses to
   boot on anything lower. In the *PHP extensions* tab make sure `pdo_mysql`,
   `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`,
   `curl` and `gd` are ticked.

## 2. Database

1. **Databases → MySQL Databases** → create a database and a user, give the user
   all privileges. Note the *full* names — Hostinger prefixes them
   (e.g. `u123456789_rhl`).
2. Open **phpMyAdmin** for that database → **Import** → upload `rhl_database.sql`
   → Go. It creates 33 tables and loads the live content (13 projects, 7 news
   items, menus, settings, the admin user, 12 inquiries).
   The export deliberately skips `cache`, `sessions` and `jobs` rows — those are
   local runtime junk; the tables themselves are created empty.

## 3. Upload

1. Zip is easiest: **File Manager** → go to `domains/rhlproperties.com/` →
   upload `rhl-hostinger.zip` → right-click → **Extract** here.
2. **Delete Hostinger's placeholder** `public_html/index.html` (and
   `default.php` if present) — Apache serves it before `index.php` and you get a
   blank/parking page.
3. Confirm `public_html/.htaccess` survived the upload (it is a hidden file —
   in File Manager enable "show hidden files", over FTP make sure dotfiles
   transferred). Without it every URL except `/` returns 404.

## 4. Configure `laravel/.env`

Edit `laravel/.env` in File Manager and fill in the three marked blocks:

- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` from step 2 (`DB_HOST=localhost`).
- `APP_URL` — already `https://rhlproperties.com`; it must match the address
  people actually type, including whether you serve `www.` or not.
- Mail: create the mailbox first (**Emails → Email Accounts**, e.g.
  `noreply@rhlproperties.com`), then set `MAIL_USERNAME` / `MAIL_PASSWORD`.
  Host `smtp.hostinger.com`, port 465, `MAIL_SCHEME=smtps` are already set.

`APP_DEBUG=false` and `APP_ENV=production` are already set — leave them that way.

## 5. Uploaded images (no symlink needed)

Hostinger disables PHPs symlink() in the web SAPI, so the usual
`php artisan storage:link` route does not work there. This package sidesteps it:
the uploads ship inside `public_html/storage`, and `config/filesystems.php` points
the public disk root at `public_path("storage")` — so the admin writes new uploads
straight into the web root and they are served immediately.

Nothing to do in this step. After uploading, confirm `public_html/storage/hero-slides/`
holds images and that one of them opens in a browser.

## 6. SSL

**Security → SSL** → install the free certificate, then enable **Force HTTPS**.
Only after HTTPS is live should `SESSION_SECURE_COOKIE=true` stay set (it is by
default). If you are testing on a temporary `*.hostingersite.com` URL over
plain http, set it to `false` or admin login will silently fail to persist.

## 7. Optional (SSH only) — cache for speed

    cd ~/domains/rhlproperties.com/laravel
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

Re-run `php artisan config:cache` after **any** later `.env` edit, otherwise the
change is ignored. To undo: `php artisan optimize:clear`.

No cron job is needed — the app defines no scheduled tasks, and
`QUEUE_CONNECTION=sync` runs the inquiry email inline.

## 8. Verify

- `/` homepage, `/projects`, `/about` — all render with images.
- `/up` returns the Laravel health page.
- `/admin/login` — sign in as `admin@rhlproperties.com.bd` with the same
  password you use locally. That address is only a login identity, not a
  mailbox; change it under the admin profile if you want it to match the domain.
- Submit the contact form; the notification goes to the address in
  **Settings → email**, which the export sets to `info@rhlproperties.com`.

If something 500s, the log is `laravel/storage/logs/laravel-*.log`. Do **not**
turn `APP_DEBUG` back on to read it on a live site.

## 9. Later updates

Use the incremental packager instead of re-uploading everything:

    php d:/2026/developer/hsc-app-deploy/make-update.php

It diffs the project against the recorded deployed state, builds
`update-<date>.zip` in the same `laravel/` + `public_html/` shape, and warns you
about new migrations or changed dependencies. Extract it at
`~/domains/rhlproperties.com/`, overwrite when asked, then record the deploy:

    php d:/2026/developer/hsc-app-deploy/make-update.php --done

Three things it deliberately never ships, because overwriting them destroys live
data: `.env`, `laravel/storage/`, and `public_html/storage/` — the last is where
every image the client uploads through the admin lives, and it exists nowhere else.

Never re-import `rhl_database.sql` after go-live either. Once the client edits
content through the admin, the live database is the only current copy.
