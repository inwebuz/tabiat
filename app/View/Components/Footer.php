<?php

namespace App\View\Components;

use App\Helpers\Helper;
use App\Page;
use App\StaticText;
use Illuminate\View\Component;
use TCG\Voyager\Facades\Voyager;

class Footer extends Component
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
        $footerMenuItems = Helper::menuItems('footer');
        $siteLogo = setting('site.logo');
        $logo = $siteLogo ? Voyager::image($siteLogo) : '/img/logo.png';

        $address = StaticText::where('key', 'contact_address')->first()->translate()->description;

        $categories = Helper::categories();

        return view('components.footer', compact('footerMenuItems', 'logo', 'address', 'categories'));
    }
}
