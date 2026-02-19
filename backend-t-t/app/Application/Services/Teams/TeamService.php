<?php

namespace App\Application\Services\Teams;

use Illuminate\Support\Str;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;
use App\Infrastructure\Mail\TeamInvitationMail;
use App\Domain\Interfaces\TeamRepositoryInterface;

class TeamService
{

    public function __construct(private TeamRepositoryInterface $teamRepository) {}
    public function create($request)
    {
        // var_dump($request->validatedWithOwner());

        $team = $this->teamRepository->create($request->validatedWithOwner());

        if (!$team) {
            throw new \Exception("Team creation failed");
        }
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Team has been created',
            'data' => [
                'name' => $team->name,
                'members' => $team->teamMembers()
            ]
        ];
    }

    public function update($request, $team){
        $validatedRequest = $request->validated();
        // echo json_encode($validatedRequest);
        // die();
        $team = $this->teamRepository->update($validatedRequest, $team);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Team information updated successfully'
        ];
        
    }


    public function myTeams($request){
        $myTeams = $this->teamRepository->myTeams($request->user()->id);
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Your team fetched successfully',
            'data' => $myTeams
        ];
    }


    

    public function delete($team){
        $this->teamRepository->delete($team);
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Team deleted successfully'
        ];
    }

    public function list(){
        $teams = $this->teamRepository->list();
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Teams retrieved successfully',
            'data' => $teams
        ];
    }

    public function view($team){
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Team retrieved successfully',
            'data' => $team
        ];
    }

}
