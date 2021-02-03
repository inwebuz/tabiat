<?php

namespace App\Http\ViewComposers;

use App\Company;
use App\CV;
use App\Helpers\LinkItem;
use App\Service;
use App\Vacancy;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;

class SidebarProfileComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menu = collect();
        $menu->push(new LinkItem(__('main.profile'), route('profile.show')));
        $menu->push(new LinkItem(__('main.my_orders'), route('profile.orders')));
        if(auth()->check() && auth()->user()->role->name == 'user') {
            $menu->push(new LinkItem(__('main.become_a_seller'), route('profile.request-seller-status')));
        }
        //if(auth()->check() && auth()->user()->can('create_products')) {}
        if(auth()->check() && auth()->user()->isSeller()) {
            $menu->push(new LinkItem(__('main.shop'), route('profile.shop.edit')));
            $menu->push(new LinkItem(__('main.my_products'), route('profile.products')));
        }

        $view->with(compact('menu'));
    }
}
