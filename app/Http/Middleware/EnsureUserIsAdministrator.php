<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the parts of the panel only an Administrator may touch: Users and
 * Roles. These are deliberately not grantable sections — handing out the
 * ability to edit permissions is the same as handing out every permission.
 * Always stacked after EnsureUserIsAdmin, which has already established that
 * somebody active is signed in.
 */
class EnsureUserIsAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            abort(403, 'Only an administrator can open this page.');
        }

        return $next($request);
    }
}
