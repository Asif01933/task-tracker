<?php 
namespace App\Domain\Interfaces;

interface MemberRepositoryInterface
{
    public function create(array $data);
    public function update($user, array $data);
    public function findTeamMember($teamId, $userId);

    public function updateMemberRoleOrRemove($teamId, $memberId, array $data);
}