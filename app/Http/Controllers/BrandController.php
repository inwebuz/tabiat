<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * show products per page values
     */
    public $quantityPerPage = [12, 60, 120];
    public $sorts = ['created_at-desc', 'price-asc', 'price-desc'];

    public function index()
    {
        $breadcrumbs = new Breadcrumbs();
        $page = Helper::translation(Page::where('slug', 'brands')->firstOrFail());
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $brands = Brand::active()->withTranslation(app()->getLocale())->get()->translate();

        return view('brands', compact('page', 'breadcrumbs', 'brands'));
    }

    public function view(Request $request, Brand $brand)
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Helper::translation(Page::where('slug', 'brands')->firstOrFail());
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url));

        // quantity per page
        $quantityPerPage = $this->quantityPerPage;
        $quantity = $request->input('quantity', $this->quantityPerPage[0]);
        if (!in_array($quantity, $this->quantityPerPage)) {
            $quantity = $this->quantityPerPage[0];
        }

        // sort - order
        $sorts = $this->sorts;
        $sortCurrent = $request->input('sort', '');
        if (empty($sortCurrent) || !in_array($sortCurrent, $sorts)) {
            $sortCurrent = $sorts[0];
        }
        $sortRaw = explode('-', $sortCurrent);
        $sort = $sortRaw[0];
        $order = $sortRaw[1];

        $query = $brand->products()->orderBy('products.' . $sort, $order)->active();

        $productAllQuantity = $query->count();

        // get query products paginate
        $products = $query->withTranslation(app()->getLocale())->paginate($quantity);
        $links = $products->links();

        $products = $products->translate();

        $brand = Helper::translation($brand);

        $breadcrumbs->addItem(new LinkItem($brand->name, $brand->url, LinkItem::STATUS_INACTIVE));

        return view('brand', compact('page', 'breadcrumbs', 'products', 'productAllQuantity', 'brand', 'links', 'quantity', 'quantityPerPage', 'sorts', 'sortCurrent'));
    }

}
