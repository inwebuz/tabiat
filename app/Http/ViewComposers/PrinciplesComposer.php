<?php

namespace App\Http\ViewComposers;

use App\StaticText;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;
use TCG\Voyager\Facades\Voyager;

class PrinciplesComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $locale = app()->getLocale();
        $principles = StaticText::where('key', 'LIKE', 'principle_%')->orderBy('key')->withTranslation($locale)->get();
        if ($principles) {
            $principles = $principles->translate();
        }
        $view->with(compact('principles'));
    }
}
