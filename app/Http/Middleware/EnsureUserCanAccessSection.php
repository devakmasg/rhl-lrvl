<?php

namespace App\Http\Middleware;

use App\Support\AdminSections;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforces the current role's section permissions on every admin route.
 *
 * This is what makes the permissions real. Hiding a link in the sidebar is
 * cosmetic — without this, anyone could reach a section they were not granted
 * by typing its URL.
 *
 * Routes that belong to no section (profile, the forced password change,
 * logout) are left alone: everybody needs them. Users and Roles are not
 * sections either, and are guarded by EnsureUserIsAdministrator instead.
 */
class EnsureUserCanAccessSection
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $section = AdminSections::forRouteName($request->route()?->getName());

        if ($section === null || Auth::user()?->canAccessSection($section)) {
            return $next($request);
        }

        abort(403, 'Your role does not include this section.');
    }
}
