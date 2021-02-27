<?php

namespace App\View\Components;

use App\Brand;
use Illuminate\View\Component;
use TCG\Voyager\Facades\Voyager;

class Partners extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $brands = Brand::active()->latest()->take(20)->get()->translate();

        return view('components.partners', compact('brands'));
    }
}
