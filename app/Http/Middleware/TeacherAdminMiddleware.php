<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherAdminMiddleware
{
    /**
     * Handle an incoming request.
     * Allow access to admin and teacher roles only
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this feature.');
        }

        $user = auth()->user();

        // Check if user has admin or teacher role
        if (!$user->role || !in_array($user->role, ['admin', 'teacher'])) {
            return redirect()->route('dashboard')->with('error', 'Access denied. This feature is only available to administrators and teachers.');
        }

        // Check if user is active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        // Sync session for compatibility
        if (!$request->session()->has('user')) {
            $request->session()->put('user', [
                'id' => $user->id,
                'user_id' => $user->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);
        }

        return $next($request);
    }
}
