<?php

namespace App\Application\Services\TeamMembers;

use App\Domain\Interfaces\MemberRepositoryInterface;

class MemberService
{

    public function __construct(private MemberRepositoryInterface $memberRepository) {}
    public function myProfile($request)
    {
        $profile = $request->user();
        return [
            'status' => true,
            'code' => 200,
            'message' => 'Profile fetched successfully',
            'data' => [
                'name' => $profile->name,
                'email' => $profile->email,
                'lastUpdatedAt' => $profile->updated_at
            ]

        ];
    }


    public function myProfileUpdate($request)
    {

        $profile = $this->memberRepository->update($request->user(), $request->validated());

        if (!$profile) {
            throw new \Exception("Profile update is not successful");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Your profile is updated',
            'data' => [
                'name' => $profile->name,
                'email' => $profile->email,
                'lastUpdatedAt' => $profile->updated_at
            ]
        ];
    }


    public function update($request, $teamId, $memberId){
        $updatedMember = $this->memberRepository->updateMemberRoleOrRemove($teamId, $memberId, $request->all());
        if (!$updatedMember) {
            throw new \Exception("Member update is not successful");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Member updated successfully',
            'data' => [
                'member_id' => $updatedMember->id,
                'team_id' => $updatedMember->team_id,
                'user_id' => $updatedMember->user_id,
                'role' => $updatedMember->role,
                'updated_at' => $updatedMember->updated_at
            ]
        ];
    }   

    public function remove($request, $teamId, $memberId){
        $removedMember = $this->memberRepository->updateMemberRoleOrRemove($teamId, $memberId, ['action' => 'remove']);
        if ($removedMember) {
            throw new \Exception("Member removal is not successful");
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Member removed successfully',
        ];
    }
}
