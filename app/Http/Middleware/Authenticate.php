<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is not authenticated
        if (! Auth::check()) {
            // For API or AJAX requests → return JSON 401
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // For normal browser requests → redirect to login page
            return redirect()->route('login');
        }

        // User is authenticated, continue the request
        return $next($request);
    }
}
