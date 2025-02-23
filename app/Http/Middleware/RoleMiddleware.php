<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, ...$roles) {
        if (!Auth::check() || !in_array(Auth::user()->user_type, $roles)) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        return $next($request);
    }
}