<?php

namespace App\View\Components;

use App\Category;
use App\Helpers\Helper;
use App\Page;
use App\StaticText;
use Illuminate\View\Component;
use TCG\Voyager\Facades\Voyager;

class Header extends Component
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
        // $banner = Helper::banner('top_1');
        $menuItems = Helper::menuItems();
        $headerMenuItems = Helper::menuItems('header');
        $pageContact = Page::find(2)->translate();
        $siteLogo = setting('site.logo');
        $logo = $siteLogo ? Voyager::image($siteLogo) : '/img/logo.png';

        $switcher = Helper::languageSwitcher();
        $activeLanguageRegional = Helper::getActiveLanguageRegional();

        $address = StaticText::where('key', 'contact_address')->first()->translate()->description;

        $cartQuantity = app('cart')->getTotalQuantity();
        $wishlistQuantity = app('wishlist')->getTotalQuantity();

        $categories = Helper::categories();

        $q = request('q', '');

        $badEye = json_decode(request()->cookie('bad_eye'), true);
        if (!$badEye) {
            $badEye = [
                'font_size' => 'normal',
                'contrast' => 'normal',
                'images' => 'normal',
            ];
        }

        return view('components.header', compact('menuItems', 'headerMenuItems', 'categories', 'cartQuantity', 'wishlistQuantity', 'pageContact', 'logo', 'switcher', 'activeLanguageRegional', 'q', 'address', 'badEye'));
    }
}
