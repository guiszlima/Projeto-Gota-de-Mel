<?php

namespace App\Livewire;

use Livewire\Component;

class PayNoIntegration extends Component
{

    public $sell;
    public function render()
    {
        return view('livewire.pay-no-integration');
    }
    public function mount($sell): void{
        $this->sell = $sell;
    }
}