<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class NavBar extends Component
{
    public $user;
    public function mount(){
        $this->user = Auth::user();
    }
    public function render()
    {
        
        return view('livewire.nav-bar');
    }
}