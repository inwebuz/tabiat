<?php

namespace App\Http\ViewComposers;

use App\Company;
use App\CV;
use App\Service;
use App\Vacancy;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;

class SidebarComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $companies = Company::active()->vip()->has('products')->take(10)->get();
        $services = Service::active()->with('company')->latest()->take(5)->get();
        $vacancies = Vacancy::active()->with('company')->latest()->take(3)->get();
        $c_v_s = CV::active()->with('user')->latest()->take(3)->get();
        $view->with(compact('banner_1', 'banner_2', 'companies', 'services', 'vacancies', 'c_v_s'));
    }
}
