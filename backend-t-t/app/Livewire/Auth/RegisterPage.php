<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class RegisterPage extends Component
{
    public $name, $email, $password, $confirmPassword;
    public $error = '';
    public $success = '';

   

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
