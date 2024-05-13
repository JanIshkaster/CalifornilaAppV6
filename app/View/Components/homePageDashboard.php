<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class homePageDashboard extends Component
{
    public $customers_count;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $customers_count
     * @return void
     */
    public function __construct($customers_count)
    {
        $this->customers_count = $customers_count;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home-page-dashboard');
    }
}