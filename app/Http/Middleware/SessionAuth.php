<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check both Laravel Auth and session for backward compatibility
        if (!Auth::check() && !$request->session()->has('user')) {
            return redirect()->route('login');
        }

        // If user is authenticated via Laravel Auth but not in session, sync it
        if (Auth::check() && !$request->session()->has('user')) {
            $user = Auth::user();
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
