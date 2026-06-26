<?php

namespace App\Policies\Concerns;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesTeamMembership
{
    private function isTeamMember(User $user, Team $team): bool
    {
        return $team->teamMembers()
            ->where('user_id', $user->id)
            ->exists();
    }

    private function belongsToTeam(Model $model, Team $team): bool
    {
        return (int) $model->team_id === (int) $team->id;
    }

    private function isMemberOfTeamId(User $user, int $teamId): bool
    {
        return $user->teamMembers()
            ->where('team_id', $teamId)
            ->exists();
    }
}
