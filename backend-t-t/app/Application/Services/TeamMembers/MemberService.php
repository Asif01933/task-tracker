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
}
