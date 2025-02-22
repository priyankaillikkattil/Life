<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
       
        try { 
            return $next($request);
        } catch (AuthenticationException $e) { 
            // Custom error message for token expired or not logged in
            return response()->json([
                'message' => 'Token expired or not logged in'
            ], 401);
        }
    }
}
