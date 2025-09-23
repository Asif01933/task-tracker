<?php 

namespace App\Infrastructure\Repositories;

use App\Models\User;
use App\Models\TeamMember;
use App\Domain\Interfaces\MemberRepositoryInterface;

class EloquentMemberRepository implements MemberRepositoryInterface{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($profile,array $data){
        $profile->update($data);
        return $profile;
    }

    public function findTeamMember($teamId, $userId){
        return TeamMember::where('team_id', $teamId)->where('user_id', $userId)->first();
    }
}