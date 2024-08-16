<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;
class dynamicLink extends Component
{
    public $text;
    public $route;
    public $currentRoute;
    public function __construct($text, $route,$currentRoute)
    {
        $this->text = $text;
        $this->route = $route;
        $this->currentRoute =  $currentRoute;
    }
    

    public function render(): View|Closure|string
    {
        return view('components.dynamic-link');;
    }
}