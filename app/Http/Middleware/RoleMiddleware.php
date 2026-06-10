<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = $request->user();
        if ($user) {
            if ($role === 'parent') {
                if ($user->role === 'parent' || ($user->role === 'student' && session('is_parent'))) {
                    return $next($request);
                }
            } elseif ($role === 'student') {
                if ($user->role === 'student' && !session('is_parent')) {
                    return $next($request);
                }
            } else {
                if ($user->role === $role) {
                    return $next($request);
                }
            }
        }
        abort(403, 'Unauthorized action.');
    }
}
