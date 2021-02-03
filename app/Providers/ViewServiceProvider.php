<?php

namespace App\Providers;

use App\Http\ViewComposers\CompanyFooterComposer;
use App\Http\ViewComposers\HomeBannersComposer;
use App\Http\ViewComposers\SidebarComposer;
use App\Http\ViewComposers\SidebarProfileComposer;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\HeaderComposer;
use App\Http\ViewComposers\PrinciplesComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            ['partials.sidebar', 'partials.sidebar_small'], SidebarComposer::class
        );
        view()->composer(
            ['partials.sidebar_profile'], SidebarProfileComposer::class
        );
        view()->composer(
            ['partials.home_banners'], HomeBannersComposer::class
        );
        view()->composer(
            ['partials.principles'], PrinciplesComposer::class
        );
    }
}
