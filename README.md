# RHL Properties Ltd — Web Application

Laravel 13 application for RHL Properties Ltd — public marketing site + admin CMS.
Built under proposal AK-RHL-26001 (Blue Soft Solutions / Akram Hossen).

This is Phase 3 of the build: the public site and admin panel were first built and
signed off as static HTML (see the sibling `hsc` repo), then ported here into a
real Laravel + MySQL application.

## Stack

- PHP 8.3+, Laravel 13
- MySQL 8+
- Node.js + npm (Vite, Tailwind CSS v4 — used for the admin panel's design tokens;
  the public site reuses the original hand-authored `assets/css/style.css`)

## Prerequisites

You need PHP, Composer, and MySQL available on your machine. The easiest way on
Windows is [Laragon](https://laragon.org/) — it bundles all three plus Apache/Nginx
with zero manual configuration, and can auto-create a `<folder>.test` virtual host
for this project. On macOS/Linux, install PHP 8.3+, Composer, and MySQL via your
usual package manager (Homebrew, apt, etc.), or use [Laravel Herd](https://herd.laravel.com/).

Check you have everything on `PATH`:

```bash
php -v        # 8.3 or higher
composer -V
mysql --version
node -v
npm -v
```

## Setup

1. **Clone the repo**

   ```bash
   git clone https://github.com/devakmasg/rhl-lrvl.git
   cd rhl-lrvl
   ```

2. **Install dependencies**

   ```bash
   composer install
   npm install
   ```

3. **Environment file**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Then edit `.env` and set your database connection:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hsc_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   (Adjust username/password to match your local MySQL setup — Laragon's default
   MySQL user is `root` with no password.)

4. **Create the database**

   ```bash
   mysql -u root -e "CREATE DATABASE hsc_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

5. **Run migrations and seed demo data**

   ```bash
   php artisan migrate --seed
   ```

   This creates all tables and seeds them with the same Bangladesh-localised demo
   content used in the static HTML phase (12 projects, directors, team, services,
   testimonials, news, inquiries, site settings, and an admin user).

6. **Link public storage** (for project images, brochures, floor plans, news
   covers, and media library uploads)

   ```bash
   php artisan storage:link
   ```

7. **Build front-end assets**

   ```bash
   npm run build
   ```

   Or for local development with hot-reloading:

   ```bash
   npm run dev
   ```

8. **Serve the app**

   Either point a local vhost (Laragon, Herd, Apache/Nginx) at the `public/`
   directory, or use the built-in dev server:

   ```bash
   php artisan serve
   ```

   The site will be available at `http://localhost:8000` (or your vhost domain).

## Logging in

Admin panel: `/admin/login`

- Email: `admin@rhlproperties.com.bd`
- Password: `password`

**Change this password immediately** on any environment beyond local development
(Admin → Profile Settings).

## Notes

- `MAIL_MAILER` defaults to `log` in `.env.example` — inquiry-notification emails
  write to `storage/logs/laravel.log` instead of sending until real SMTP
  credentials are configured (blocked on client, see `TASKS.md` in the `hsc` repo).
- The admin media library has no dedicated database table — it's a thin wrapper
  over files stored under `storage/app/public/media/`.
- Real image resizing/compression on upload is not implemented (files are
  validated and stored as-is); add `intervention/image` if that's needed later.
