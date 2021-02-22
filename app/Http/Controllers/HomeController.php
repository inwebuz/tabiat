<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Helpers\Helper;
use App\Page;
use App\Product;
use App\Publication;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page = Page::findOrFail(1)->translate();
        $pageAbout = Page::find(3)->translate();
        $pageCatalog = Page::find(4)->translate();

        $slides = Helper::banners('slide');
        if ($slides) {
            $slides = $slides->translate();
        }
        $slide = $slides->first();

        $brands = Brand::active()->latest()->take(20)->get()->translate();
        $featuredProducts = Product::active()->featured()->latest()->take(12)->get()->translate();
        $homeCategories = Helper::categories('home');

        $news = Publication::active()->news()->latest()->take(3)->get();
        if (!$news->isEmpty()) {
            $news = $news->translate();
        }

        return view('home', compact('page', 'pageAbout', 'pageCatalog', 'slide', 'brands', 'featuredProducts', 'homeCategories', 'news'));
    }

    public function latestProducts(Category $category)
    {
        $products = $category->allProducts()->active()->latest()->take(10)->get()->translate();
        $category = $category->translate();
        return view('partials.latest_products_slider', compact('category', 'products'));
    }
}
