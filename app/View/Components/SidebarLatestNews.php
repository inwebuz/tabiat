<?php

namespace App\View\Components;

use App\Publication;
use Illuminate\View\Component;

class SidebarLatestNews extends Component
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
        $news = Publication::active()->news()->withTranslation(app()->getLocale())->latest()->take(3)->get()->translate();
        return view('components.sidebar-latest-news', compact('news'));
    }
}
