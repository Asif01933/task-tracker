<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use App\Policies\Concerns\AuthorizesTeamMembership;

class LabelPolicy
{
    use AuthorizesTeamMembership;

    public function viewAny(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }

    public function create(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }

    public function update(User $user, Label $label, Team $team): bool
    {
        return $this->belongsToTeam($label, $team)
            && $this->isTeamMember($user, $team);
    }

    public function delete(User $user, Label $label, Team $team): bool
    {
        return $this->belongsToTeam($label, $team)
            && $this->isTeamMember($user, $team);
    }

    public function attach(User $user, Team $team, Task $task): bool
    {
        return $this->belongsToTeam($task, $team)
            && $this->isTeamMember($user, $team);
    }

    public function detach(User $user, Label $label, Team $team, Task $task): bool
    {
        return $this->belongsToTeam($label, $team)
            && $this->belongsToTeam($task, $team)
            && $this->isTeamMember($user, $team);
    }
}
