<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * The gate on every admin page: signed in, still active, and not overdue for a
 * first-login password change. Role is not checked here — see
 * EnsureUserIsAdministrator for the admin-only areas.
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Checked on every request, not just at login, so switching someone off
        // ends the session they already have open rather than waiting them out.
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'This account has been deactivated. Please contact an administrator.']);
        }

        // A password an administrator typed for them is a temporary one; nothing
        // else in the panel opens until they have replaced it.
        if ($user->must_change_password && ! $request->routeIs('admin.password.change', 'admin.password.change.update', 'admin.logout')) {
            return redirect()->route('admin.password.change');
        }

        return $next($request);
    }
}
