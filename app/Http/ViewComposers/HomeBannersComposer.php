<?php

namespace App\Http\ViewComposers;

use App\Category;
use App\Page;
use App\StaticText;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;
use TCG\Voyager\Facades\Voyager;

class HomeBannersComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $banners = collect();
        $home_1 = Helper::banner('home_1');
        $home_2 = Helper::banner('home_2');
        $home_3 = Helper::banner('home_3');
        $banners->push($home_1);
        $banners->push($home_2);
        $banners->push($home_3);

        $view->with(compact('banners'));
    }
}
