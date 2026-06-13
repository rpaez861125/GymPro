<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /* Handle an incoming request.
         Uso: middleware('role:admin') o middleware('role:admin|manager') */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $allowed = array_filter(array_map('trim', explode('|', $roles)));
        $userRole = (string) $user->role;

        if (!in_array($userRole, $allowed, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
                'required_roles' => $allowed,
                'your_role' => $userRole,
            ], 403);
        }

        return $next($request);
    }
}
