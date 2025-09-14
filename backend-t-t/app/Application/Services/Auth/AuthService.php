<?php

namespace App\Application\Services\Auth;

use App\Models\User;
use App\Application\DTOs\MemberDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Domain\Interfaces\MemberRepositoryInterface;

class AuthService
{

    public function __construct(private MemberRepositoryInterface $memberRepository) {}
    public function register(MemberDTO $memberDTO)
    {
        // Hash the password
        $memberDTO->password = Hash::make($memberDTO->password);

        // Use repository to save user
        $member = $this->memberRepository->create([
            'name' => $memberDTO->name,
            'email' => $memberDTO->email,
            'password' => $memberDTO->password,
        ]);

        return [
            'status' => true,
            'code' => 200,
            'message' => 'User registered successfully',
            'data' => $member
        ];
    }


    public function login(MemberDTO $memberDTO)
    {
        // Build credentials array manually (DTO doesn’t have `only()`)
        $credentials = [
            'email'    => $memberDTO->email,
            'password' => $memberDTO->password,
        ];

        if (!Auth::attempt($credentials)) {
            return [
                'status'  => false,
                'code'    => 401,
                'message' => 'Invalid email or password'
            ];
        }

        $user = User::where('email', $memberDTO->email)->first();

        return [
            'status'  => true,
            'code'    => 200,
            'message' => 'Login successful',
            'token'   => $user->createToken('API Token')->plainTextToken,
            'data'    => [
                'name' => $user->name,
                'email' => $user->email
            ],
        ];
    }
}
