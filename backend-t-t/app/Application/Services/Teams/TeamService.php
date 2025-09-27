<?php

namespace App\Application\Services\Teams;

use Illuminate\Support\Str;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;
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


    public function invite($request){
        $token = Str::uuid()->toString();
        $data = $request->validated();
        $data['token'] = $token;
        $data['status'] = 'pending';
        $invitation = $this->teamRepository->invite($data);
        

        $inviteUrl = url("/invitations/accept/{$token}");
        $teamName = $invitation->team->name ?? 'Team';

        Mail::to($data['email'])->send(new TeamInvitationMail($inviteUrl, $teamName));
    }
}
