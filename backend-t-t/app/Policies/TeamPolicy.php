<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\Policies\Concerns\AuthorizesTeamMembership;

class TeamPolicy
{
    use AuthorizesTeamMembership;

    public function view(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }

    public function members(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }
}
