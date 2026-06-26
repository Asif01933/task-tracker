<?php

namespace App\Policies;

use App\Models\ReportReceiver;
use App\Models\Team;
use App\Models\User;
use App\Policies\Concerns\AuthorizesTeamMembership;

class ReportReceiverPolicy
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

    public function update(User $user, ReportReceiver $reportReceiver): bool
    {
        return $this->isMemberOfTeamId($user, (int) $reportReceiver->team_id);
    }
}
