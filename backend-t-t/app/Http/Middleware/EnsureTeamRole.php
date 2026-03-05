<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamRole
{
    /**
     * Ensure the authenticated user has one of the given roles in the current team context.
     * Must be used after TeamContextMiddleware so setPermissionsTeamId() is set.
     *
     * @param  array<string>  $roles  Role names (e.g. ['owner', 'admin'])
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'You do not have the required role in this team.',
        ], 403);
    }
}
