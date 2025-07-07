<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip check for guests
        if (!$user) {
            return $next($request);
        }

        // Skip check for change password routes and logout
        $exemptRoutes = [
            'password.change.form',
            'password.change',
            'logout',
            'login',
        ];

        if (in_array($request->route()->getName(), $exemptRoutes)) {
            return $next($request);
        }

        // Check if user needs to change password
        if (!$user->password_changed) {
            return redirect()->route('password.change.form')
                           ->with('info', 'Silakan ubah password default Anda untuk melanjutkan.');
        }

        return $next($request);
    }
}
