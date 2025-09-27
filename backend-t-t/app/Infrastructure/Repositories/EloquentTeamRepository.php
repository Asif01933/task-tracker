<?php 

namespace App\Infrastructure\Repositories;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamInvitation;
use App\Domain\Interfaces\TeamRepositoryInterface;

class EloquentTeamRepository implements TeamRepositoryInterface{
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

    
}