<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LoginPage extends Component
{
    public $email = '';
    public $password = '';
    public $errorMessage = '';

    protected $listeners = ['googleLogin'];

    public function goHome()
    {
        return redirect()->route('landing');
    }

    public function handleLogin()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $response = Http::post(config('app.api_url') . '/login', [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($response->failed()) {
                $this->errorMessage = $response->json('message') ?? 'Login failed. Please try again.';
                return;
            }

            session(['token' => $response->json('token')]);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->errorMessage = 'Something went wrong. Please try again later.';
        }
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
