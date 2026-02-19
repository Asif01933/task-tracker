<?php 
namespace App\Http\Controllers\Teams;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\TeamCreateRequest;
use App\Http\Requests\Teams\TeamInviteRequest;
use App\Http\Requests\Teams\TeamUpdateRequest;
use App\Application\Services\Teams\TeamService;
use App\Http\Requests\Teams\TeamInvitationAcceptRequest;
use Illuminate\Http\Request;

class TeamController extends Controller{

    public function __construct(private TeamService $teamService){}
    public function create(TeamCreateRequest $request){

        return response()->json($this->teamService->create($request));
    }

    public function update(TeamUpdateRequest $request, Team $team){
        return response()->json($this->teamService->update($request, $team));
    }

    public function myTeams(Request $request){
        return response()->json($this->teamService->myTeams($request));
    }

    public function delete(Team $team){
        return response()->json($this->teamService->delete($team));
    }

    public function list(){
        return response()->json($this->teamService->list());
    }

    public function view(Team $team){
        return response()->json($this->teamService->view($team));
    }

}