<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class buttonBack extends Component
{
    public $route;

    /**
     * Create a new component instance.
     */
    public function __construct($route = null)
    {
        // Se nenhuma rota for passada, usa uma rota padrão
        $this->route = $route; // Substitua 'ultima.rota' pela rota padrão desejada
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-back');
    }
}