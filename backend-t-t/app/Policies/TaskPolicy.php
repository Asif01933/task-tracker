<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }

    public function create(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }

    public function view(User $user, Task $task, Team $team): bool
    {
        return $this->taskBelongsToTeam($task, $team)
            && $this->isTeamMember($user, $team);
    }

    public function update(User $user, Task $task, Team $team): bool
    {
        return $this->taskBelongsToTeam($task, $team)
            && $this->isTeamMember($user, $team);
    }

    public function delete(User $user, Task $task, Team $team): bool
    {
        return $this->taskBelongsToTeam($task, $team)
            && $this->isTeamMember($user, $team);
    }

    private function isTeamMember(User $user, Team $team): bool
    {
        return $team->teamMembers()
            ->where('user_id', $user->id)
            ->exists();
    }

    private function taskBelongsToTeam(Task $task, Team $team): bool
    {
        return (int) $task->team_id === (int) $team->id;
    }
}
