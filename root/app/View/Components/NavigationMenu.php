<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavigationMenu extends Component
{

    public array $menu;

    public function __construct(array $menu)
    {
        $this->menu = $menu;
    }

    public function render()
    {
        return view('components.navigation-menu');
    }
}
