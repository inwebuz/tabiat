<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $breadcrumbs = new Breadcrumbs();
        $page = Page::where('slug', 'shops')->active()->firstOrFail()->translate();
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));
        $shops = Shop::active()->get();
        $shops = $shops->translate();
        return view('shop.index', compact('page', 'breadcrumbs', 'shops'));
    }

    public function show(Shop $shop)
    {
        $breadcrumbs = new Breadcrumbs();
        $page = Page::where('slug', 'shops')->active()->firstOrFail()->translate();
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url));
        $products = $shop->products()->active()->paginate(24);
        $links = $products->links();

        $products = $products->translate();
        $shop = $shop->translate();
        $breadcrumbs->addItem(new LinkItem($shop->name, $shop->url, LinkItem::STATUS_INACTIVE));
        return view('shop.show', compact('page', 'breadcrumbs', 'shop', 'products', 'links'));
    }
}
