<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use App\Application\DTOs\MemberDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Application\Services\Auth\AuthService;
use App\Http\Requests\Auth\RegistrationRequest;

class AuthController extends Controller
{

    public function __construct(private AuthService $authService) {}
    /**
     * register function will be used for 
     * register
     */
    public function register(RegistrationRequest $request)
    {


        $memberDTO = new MemberDTO(
            $request->name,
            $request->email,
            $request->password
        );

        return response()->json($this->authService->register($memberDTO));
    }

    /**
     * Below function is responsible for login
     */
    public function login(LoginRequest $request)
    {

        $memberDto = new MemberDTO(
            '',
            $request->email,
            $request->password
        );

        return response()->json($this->authService->login($memberDto));
    }

    public function logout(Request $request)
    {
        return response()->json($this->authService->logout($request));
        
    }
}
