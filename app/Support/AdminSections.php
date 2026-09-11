<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * The single list of admin sections a role can be granted.
 *
 * Everything permission-related reads from here: the sidebar renders from it,
 * EnsureUserCanAccessSection resolves the current route through it, and the
 * Roles page builds its checkboxes from it. Adding an admin section means
 * adding one entry here — it then appears in the menu and in the permission
 * screen together, so the two can never drift apart.
 *
 * `patterns` are route-name patterns in routeIs() form, and must cover every
 * route the section owns, not just the one the menu links to — a section that
 * lists `admin.projects.*` also gets its image and floor-plan sub-routes, and a
 * page built from two controllers (Leaders & Team) simply names both.
 *
 * Users and Roles are deliberately absent: they are never grantable, and stay
 * behind EnsureUserIsAdministrator instead.
 */
class AdminSections
{
    /**
     * Sidebar group order. Sections render grouped under these headings.
     */
    public const GROUPS = ['Overview', 'Pages', 'Collections', 'Site-wide'];

    /**
     * @return array<string, array{label: string, group: string, route: string, patterns: array<int, string>, icon: string, note?: string}>
     */
    public static function all(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard',
                'group' => 'Overview',
                'route' => 'admin.dashboard',
                'patterns' => ['admin.dashboard'],
                'note' => 'The landing page. Its inquiry panel only shows to roles that also have Inquiries.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>',
            ],
            'inquiries' => [
                'label' => 'Inquiries',
                'group' => 'Overview',
                'route' => 'admin.inquiries.index',
                'patterns' => ['admin.inquiries.*'],
                'note' => 'Customer names, phone numbers and budgets. The only section holding personal data.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>',
            ],

            'content-home' => [
                'label' => 'Homepage',
                'group' => 'Pages',
                'route' => 'admin.content.home',
                'patterns' => ['admin.content.home', 'admin.content.home.*'],
                'note' => 'Includes the hero slider, journey chapters and explore slides.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg>',
            ],
            'content-about' => [
                'label' => 'About & Mission',
                'group' => 'Pages',
                'route' => 'admin.content.about',
                'patterns' => ['admin.content.about', 'admin.content.about.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>',
            ],
            'content-landowners' => [
                'label' => 'Landowners Page',
                'group' => 'Pages',
                'route' => 'admin.content.landowners',
                'patterns' => ['admin.content.landowners', 'admin.content.landowners.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20h18M5 20V9l7-5 7 5v11"/><path d="M10 20v-6h4v6"/></svg>',
            ],
            'content-partners' => [
                'label' => 'Partners Page',
                'group' => 'Pages',
                'route' => 'admin.content.partners',
                'patterns' => ['admin.content.partners', 'admin.content.partners.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            ],
            'page-banners' => [
                'label' => 'Page Headers',
                'group' => 'Pages',
                'route' => 'admin.page-banners.index',
                'patterns' => ['admin.page-banners.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>',
            ],
            'page-sections' => [
                'label' => 'Page Sections',
                'group' => 'Pages',
                'route' => 'admin.page-sections.index',
                'patterns' => ['admin.page-sections.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 9v12"/></svg>',
            ],
            'cta-blocks' => [
                'label' => 'Page CTAs',
                'group' => 'Pages',
                'route' => 'admin.cta-blocks.index',
                'patterns' => ['admin.cta-blocks.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="15" width="8" height="5" rx="1.5"/><rect x="13" y="15" width="8" height="5" rx="1.5"/></svg>',
            ],

            'projects' => [
                'label' => 'Projects',
                'group' => 'Collections',
                'route' => 'admin.projects.index',
                'patterns' => ['admin.projects.*'],
                'note' => 'Includes each project&rsquo;s images, floor plans and units.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/></svg>',
            ],
            'project-locations' => [
                'label' => 'Project Locations',
                'group' => 'Collections',
                'route' => 'admin.project-locations.index',
                'patterns' => ['admin.project-locations.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
            ],
            'news' => [
                'label' => 'News',
                'group' => 'Collections',
                'route' => 'admin.news.index',
                'patterns' => ['admin.news.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h13a2 2 0 0 1 2 2v13a1 1 0 0 1-1.7.7L15 17H6a2 2 0 0 1-2-2V4Z"/><path d="M8 9h8M8 13h5"/></svg>',
            ],
            'services' => [
                'label' => 'Services',
                'group' => 'Collections',
                'route' => 'admin.services.index',
                'patterns' => ['admin.services.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94Z"/></svg>',
            ],
            'achievements' => [
                'label' => 'Achievements',
                'group' => 'Collections',
                'route' => 'admin.achievements.index',
                'patterns' => ['admin.achievements.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>',
            ],
            'testimonials' => [
                'label' => 'Testimonials',
                'group' => 'Collections',
                'route' => 'admin.testimonials.index',
                'patterns' => ['admin.testimonials.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
            ],
            'team' => [
                'label' => 'Leaders & Team',
                'group' => 'Collections',
                'route' => 'admin.directors.index',
                // One page, two controllers — the mockup merges directors and
                // team members onto a single screen.
                'patterns' => ['admin.directors.*', 'admin.team.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            ],

            'contact-numbers' => [
                'label' => 'Contact Numbers',
                'group' => 'Site-wide',
                'route' => 'admin.contact-numbers.index',
                'patterns' => ['admin.contact-numbers.*'],
                'note' => 'The phone numbers shown in the footer and on the contact pages.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.1 9.9a16 16 0 0 0 6 6l1.26-1.26a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>',
            ],
            'partners' => [
                'label' => 'Trusted Partners',
                'group' => 'Site-wide',
                'route' => 'admin.partners.index',
                'patterns' => ['admin.partners.*'],
                'note' => 'The partner logo strip — not the same as the Partners Page.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M3.5 15h17"/><path d="M12 3c2.5 3 2.5 15 0 18-2.5-3-2.5-15 0-18Z"/></svg>',
            ],
            'menus' => [
                'label' => 'Menus',
                'group' => 'Site-wide',
                'route' => 'admin.menus.index',
                'patterns' => ['admin.menus.*'],
                'note' => 'Site navigation. Affects every page.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
            ],
            'settings' => [
                'label' => 'Site Settings',
                'group' => 'Site-wide',
                'route' => 'admin.settings.edit',
                'patterns' => ['admin.settings.*'],
                'note' => 'Company name, logo and contact details, used across the whole site.',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
            ],
            'media' => [
                'label' => 'Media Library',
                'group' => 'Site-wide',
                'route' => 'admin.media.index',
                'patterns' => ['admin.media.*'],
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>',
            ],
        ];
    }

    /**
     * Every valid section key — what a role's permission list is validated against.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(static::all());
    }

    /**
     * Sections bucketed by sidebar group, in GROUPS order. Used by both the
     * sidebar and the Roles permission checkboxes.
     *
     * @return array<string, array<string, array<string, mixed>>>
     */
    public static function grouped(): array
    {
        $grouped = array_fill_keys(static::GROUPS, []);

        foreach (static::all() as $key => $section) {
            $grouped[$section['group']][$key] = $section;
        }

        return $grouped;
    }

    /**
     * Which section a route name belongs to, or null when the route is not
     * permission-controlled (profile, logout, the forced password change).
     */
    public static function forRouteName(?string $routeName): ?string
    {
        if ($routeName === null) {
            return null;
        }

        foreach (static::all() as $key => $section) {
            if (Str::is($section['patterns'], $routeName)) {
                return $key;
            }
        }

        return null;
    }
}
