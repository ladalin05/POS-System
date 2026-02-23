<?php

namespace App\View\Components;

use App\Models\Admin\Menu;
use Illuminate\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */

    public function __construct()
    {
        // 
    }

    public function render(): View
    {

        return view('layouts.app');
    }
}
