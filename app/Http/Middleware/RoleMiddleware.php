<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (auth('api')->user()->role != $role) {

            return response()->json([
                'message' => 'Access Denied'
            ], 403);
        }

        return $next($request);
    }
}