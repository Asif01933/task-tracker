<?php

namespace App\Application\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Application\DTOs\MemberDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Domain\Interfaces\MemberRepositoryInterface;
use Google\Client as GoogleClient;
class AuthService
{

    public function __construct(private MemberRepositoryInterface $memberRepository) {}
    public function register($request)
    {

        $requestValues = $request->validated();

        $requestValues['password'] = Hash::make($requestValues['password']);


        $member = $this->memberRepository->create($requestValues);

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

    public function googleLogin($request)
    {





        try {
            $validatedRequest = $request->validated();
            // Get user info from Google using Socialite (stateless)
            $client = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]);

            // Verify the token and get payload
            $payload = $client->verifyIdToken($validatedRequest['credential']);

            

            $email = $payload['email'];
            $name = $payload['name'] ?? 'Unknown';

            // Create or update user based on email only
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt(Str::random(16)), // random password
                ]
            );

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
        } catch (\Exception $e) {
            throw new \Exception("Google login failed");
            
        }
    }
    public function logout($request)
    {
        $user = $request->user();

        if (!$user) {
            return [
                'status'  => false,
                'code'    => 401,
                'message' => 'Already logged out or token is invalid'
            ];
        }


        if (!$user->tokens()->exists()) {
            return [
                'status'  => false,
                'code'    => 400,
                'message' => 'No active session found'
            ];
        }


        $user->currentAccessToken()->delete();

        return [
            'status'  => true,
            'code'    => 200,
            'message' => 'Logged out successfully'
        ];
    }
}
