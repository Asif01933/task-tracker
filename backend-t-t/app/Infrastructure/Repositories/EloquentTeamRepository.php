<?php 

namespace App\Infrastructure\Repositories;

use App\Models\Team;
use App\Domain\Interfaces\TeamRepositoryInterface;
use App\Models\TeamMember;

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

    
}