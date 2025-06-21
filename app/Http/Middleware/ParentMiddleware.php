<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        if (!$request->session()->has('user')) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        // Check if user has parent role
        $user = $request->session()->get('user');
        if (!isset($user['role']) || $user['role'] !== 'parent') {
            return redirect('/dashboard')->with('error', 'Access denied. This page is for parents only.');
        }

        return $next($request);
    }
}
