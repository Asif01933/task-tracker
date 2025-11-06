<?php

namespace App\Livewire\Auth;

use App\Application\DTOs\MemberDTO;
use App\Application\Services\Auth\AuthService;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LoginPage extends Component
{
    public $email = '';
    public $password = '';
    public $errorMessage = '';

    protected $listeners = ['googleLogin'];

    private $authService;
    public function boot(AuthService $authService){
        $this->authService = $authService;
    }
    public function goHome()
    {
        return redirect('/');
    }

    public function handleLogin()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $memberDto = new MemberDTO(
            '',
            $this->email,
            $this->password
        );

        // Call the service
        $response = $this->authService->login($memberDto);

        // Handle response
        if (!$response['status']) {
            $this->errorMessage = $response['message'];
            return; // Stop execution here and show error
        }

        // ✅ If login successful, store token or redirect
        session(['token' => $response['token']]);

        return redirect('/dashboard'); // Make sure this route exists
        
    }

    public function googleLogin($payload)
    {
        try {
            $response = Http::post(config('app.api_url') . '/google/login', [
                'credential' => $payload['credential'],
            ]);

            if ($response->failed()) {
                $this->errorMessage = 'Google sign-in failed.';
                return;
            }

            session(['token' => $response->json('token')]);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->errorMessage = 'Google sign-in failed.';
        }
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
