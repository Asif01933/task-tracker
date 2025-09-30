<?php 

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\InvitationReporistoryInterface;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamInvitation;
use App\Domain\Interfaces\TeamRepositoryInterface;

class EloquentInvitationRepository implements InvitationReporistoryInterface{
    public function create(array $data)
    {
        $team = Team::create($data);
        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $team->owner_id,
            'role' => 'admin',
            'status' => 'active'
        ]);
        
        return $team;
    }

    public function invite(array $data){
        return TeamInvitation::create($data);
    }

    public function findMemberByToken($token, $email){
        return TeamInvitation::where('token', $token)
        ->where('status', 'pending')
        ->where('email', $email) // ✅ email cross-check
        ->first();
    }

    public function acceptInvitation(array $data){
        return TeamMember::create($data);
    }

    
}