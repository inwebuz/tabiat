<?php

namespace App\View\Components;

use App\Helpers\Helper;
use Illuminate\View\Component;

class SidebarSiblingPages extends Component
{
    public $page;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($page = null)
    {
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $siblingPages = collect();
        if (!empty($this->page)) {
            $siblingPages = Helper::siblingPages($this->page->getModel());
        }
        return view('components.sidebar-sibling-pages', compact('siblingPages'));
    }
}
