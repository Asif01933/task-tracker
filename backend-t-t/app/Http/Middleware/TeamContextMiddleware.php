<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class TeamContextMiddleware
{
    /**
     * Set the active team context for Spatie Permission so that
     * role/permission checks are scoped to the current team.
     *
     * Expects a route parameter named 'team' (e.g. /teams/{team}/tasks).
     * The {team} value can be an id or a resolved Team model (route model binding).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $team = $request->route('team');

        if ($team === null) {
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
            return $next($request);
        }

        $teamId = $team instanceof \Illuminate\Database\Eloquent\Model
            ? $team->getKey()
            : (int) $team;

        app(PermissionRegistrar::class)->setPermissionsTeamId($teamId);

        return $next($request);
    }
}
