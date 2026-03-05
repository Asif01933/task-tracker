<?php

namespace App\Application\Services\Invitation;

use Illuminate\Support\Str;
use App\Models\TeamInvitation;
use Illuminate\Support\Facades\Mail;
use App\Infrastructure\Mail\TeamInvitationMail;
use App\Domain\Interfaces\InvitationReporistoryInterface;

class InvitationService
{

    public function __construct(private InvitationReporistoryInterface $invitationReporistoryInterface) {}
    public function invite($request)
    {
        $token = Str::uuid()->toString();
        $data = $request->validated();
        $data['token'] = $token;
        $data['status'] = 'pending';
        $invitation = $this->invitationReporistoryInterface->invite($data);


        $inviteUrl = url("/invitations/accept/{$token}");
        $teamName = $invitation->team->name ?? 'Team';

        Mail::to($data['email'])->send(new TeamInvitationMail($inviteUrl, $teamName));

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Invitation sent successfully'
        ];
    }

    public function acceptInvitation($request)
    {

        $user = $request->user();
        $token = $request->token;
        $invitation = $this->invitationReporistoryInterface->findMemberByToken($token, $user->email);
        
        if (! $invitation) {
            return false; // invalid or stolen token
        }
        $role = $invitation->role ?? 'member';
        $data = [
            'team_id' => $invitation->team_id,
            'user_id' => $user->id,
            'role' => $role,
            'status' => 'active'
        ];
        $this->invitationReporistoryInterface->acceptInvitation($data);

        $team = $invitation->team;
        $user->assignRole($role, $team);

        $invitation->update(['status' => 'accepted']);
        return [
            'status' => true,
            'code' => 200,
            'message' => 'You successfully entered into the team!'
        ];
    }
}
