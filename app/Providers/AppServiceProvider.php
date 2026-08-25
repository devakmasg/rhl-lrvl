<?php

namespace App\Providers;

use App\Models\PageBanner;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * The footer and nav sit in the shared layout, so they render on every
         * page — including ones whose controller never loads Setting. Binding
         * it here is what lets them show the admin-managed contact details
         * instead of hardcoded ones.
         *
         * Resolved lazily so a request that never renders the layout doesn't
         * pay for the query, and guarded on the table existing so `migrate`
         * on a fresh database can still boot.
         */
        View::composer(['partials.footer', 'partials.nav'], function ($view) {
            static $setting = null;

            if ($setting === null) {
                $setting = Schema::hasTable('settings') ? Setting::first() : false;
            }

            $view->with('setting', $setting ?: null);
        });

        /**
         * Page headers and per-page SEO come from one row keyed by route name,
         * so a page's Blade only has to @include the shared partial. Bound on
         * the layout too, so <head> can read the same row for title/meta.
         */
        View::composer(['partials.page-header', 'layouts.app', 'pages.*'], function ($view) {
            if (! Schema::hasTable('page_banners')) {
                return;
            }

            // A page may pass its own $banner (e.g. a detail page building one
            // from the record being shown) — don't overwrite that.
            if (array_key_exists('banner', $view->getData())) {
                return;
            }

            $key = $this->bannerKeyForCurrentRoute();

            $view->with('banner', $key ? PageBanner::forKey($key) : null);
        });
    }

    /**
     * Map the current route name onto a page_banners key. Route names and keys
     * line up except for the resource-style names ("projects.index"), which
     * collapse to their listing page.
     */
    private function bannerKeyForCurrentRoute(): ?string
    {
        $name = Route::currentRouteName();

        if (! $name) {
            return null;
        }

        return match ($name) {
            'projects.index' => 'projects',
            'news.index' => 'news',
            default => str_contains($name, '.') ? null : $name,
        };
    }
}
