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
        $validatedRequest = $request->validated;
        $team = $this->teamRepository->update($validatedRequest, $team);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Team information updated successfully'
        ];
        
    }

}
