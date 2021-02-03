<?php

namespace App\View\Components;

use App\Page;
use Illuminate\View\Component;

class ImportantLinks extends Component
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
        $pages = [];
        $pages[] = Page::where('slug', 'about')->firstOrFail()->translate();
        $pages[] = Page::where('slug', 'news')->firstOrFail()->translate();
        return view('components.important-links', compact('pages'));
    }
}
