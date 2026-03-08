<?php

namespace App\Application\Services\Teams;

use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Infrastructure\Mail\TeamInvitationMail;
use App\Models\TeamInvitation;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

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

        $permissionRegistrar = app(PermissionRegistrar::class);
        $previousTeamId = $permissionRegistrar->getPermissionsTeamId();
        $permissionRegistrar->setPermissionsTeamId($team->getKey());
        $team->owner->assignRole('owner');
        $permissionRegistrar->setPermissionsTeamId($previousTeamId);

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


    public function members($team){

        if(!$team){
            throw new \Exception("Team not found");
        }

        if(!$team->teamMembers()->where('user_id', auth()->user()->id)->exists()){
            throw new \Exception("You are not a member of this team");
        }

        $members = [];

        foreach($team->teamMembers as $member){
            $members[] = [
                'id' => $member->id,
                'name' => $member->user->name,
                'email' => $member->user->email,
                'role' => $member->role,
                'status' => $member->status,
                
            ];
        }


        return [
            'status' => true,
            'code' => 200,
            'message' => 'Team members retrieved successfully',
            'data' => $members
        ];
    }

}
