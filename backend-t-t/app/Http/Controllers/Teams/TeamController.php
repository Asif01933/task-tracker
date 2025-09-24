<?php 
namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\TeamCreateRequest;
use App\Http\Requests\Teams\TeamInviteRequest;
use App\Application\Services\Teams\TeamService;


class TeamController extends Controller{

    public function __construct(private TeamService $teamService){}
    public function create(TeamCreateRequest $request){

        return response()->json($this->teamService->create($request));
    }


    public function invite(TeamInviteRequest $request){
        return response()->json($this->teamService->invite($request));
    }
}