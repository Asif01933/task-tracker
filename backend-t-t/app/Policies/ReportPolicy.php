<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\Team;
use App\Models\User;
use App\Policies\Concerns\AuthorizesTeamMembership;

class ReportPolicy
{
    use AuthorizesTeamMembership;

    public function download(User $user, Team $team): bool
    {
        return $this->isTeamMember($user, $team);
    }

    public function send(User $user, Report $report): bool
    {
        return $this->isMemberOfTeamId($user, (int) $report->team_id);
    }
}
