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

    public function updateMemberRoleOrRemove($teamId, $memberId, array $data){
        $member = TeamMember::where('team_id', $teamId)->where('id', $memberId)->first();
        if (!$member) {
            return null;
        }

        if (isset($data['action']) && $data['action'] === 'remove') {
            $member->delete();
            return null;
        }

        if (isset($data['role'])) {
            $member->role = $data['role'];
            $member->save();
        }

        return $member;
    }
}