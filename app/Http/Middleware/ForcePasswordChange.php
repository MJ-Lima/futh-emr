<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirects authenticated users who have `must_change_password = true`
 * to the forced password-change page. This applies to the staff (web)
 * guard — the Filament admin panel.
 *
 * The middleware allows the password-change route itself, plus logout,
 * to pass through so the user isn't trapped in an infinite redirect.
 */
class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Only applies to the web (staff) guard — patient_portal users
        // have their own separate flow.
        if ($user instanceof \App\Models\User && $user->must_change_password) {
            $allowedRoutes = [
                'admin.force-password-change',
                'filament.admin.auth.logout',
                'password.update',
            ];

            $currentRoute = $request->route()?->getName();

            if (! in_array($currentRoute, $allowedRoutes, true)) {
                return Redirect::route('admin.force-password-change')
                    ->with('warning', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }
}
