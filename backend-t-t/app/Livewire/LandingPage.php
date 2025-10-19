<?php
namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public $name = '';

    public function render()
    {
        $this->name = 'Asif';
        return view('livewire.landing-page');
    }
}
