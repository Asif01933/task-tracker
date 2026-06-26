<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\TeamCreateRequest;
use App\Http\Requests\Teams\TeamUpdateRequest;
use App\Application\Services\Teams\TeamService;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Team controller with team-based RBAC.
 *
 * Routes that include {team} use TeamContextMiddleware, so role checks are
 * scoped to the current team. Example (when team context is set):
 *
 *   if (! $request->user()->hasRole('admin')) {
 *       return response()->json(['message' => 'Forbidden'], 403);
 *   }
 *
 * Or use the team.role middleware: 'team.role:owner,admin'
 */
class TeamController extends Controller
{
    public function __construct(private TeamService $teamService) {}

    public function create(TeamCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->teamService->create($request));
    }

    /**
     * Update team. Restricted to owner/admin via team.role middleware.
     */
    public function update(TeamUpdateRequest $request, Team $team): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->teamService->update($request, $team));
    }

    public function myTeams(Request $request){
        return response()->json($this->teamService->myTeams($request));
    }

    /**
     * Delete team. Restricted to owner/admin via team.role middleware.
     */
    public function delete(Team $team): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->teamService->delete($team));
    }

    public function list(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->teamService->list());
    }

    public function view(Team $team): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('view', $team);

        return response()->json($this->teamService->view($team));
    }

    public function members(Team $team): \Illuminate\Http\JsonResponse
    {
        Gate::authorize('members', $team);

        return response()->json($this->teamService->members($team));
    }
}
