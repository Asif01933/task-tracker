<?php

namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public function goToLogin()
    {
        return redirect()->route('login');
    }

    public function goToRegister()
    {
        return redirect()->route('register');
    }

    public function scrollToFeatures()
    {
        $this->dispatch('scrollToFeatures');
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
