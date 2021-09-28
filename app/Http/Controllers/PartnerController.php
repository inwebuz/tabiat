<?php

namespace App\Http\Controllers;

use App\Partner;
use App\Category;
use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Product;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * show products per page values
     */
    public $quantityPerPage = [12, 60, 120];
    public $sorts = ['created_at-desc', 'price-asc', 'price-desc'];

    public function index()
    {
        $breadcrumbs = new Breadcrumbs();
        $page = Helper::translation(Page::where('slug', 'partners')->firstOrFail());
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $partners = Partner::active()->withTranslation(app()->getLocale())->get()->translate();

        return view('partners', compact('page', 'breadcrumbs', 'partners'));
    }

    public function view(Request $request, Partner $partner)
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Helper::translation(Page::where('slug', 'partners')->firstOrFail());
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

        $query = $partner->products()->orderBy('products.' . $sort, $order)->active();

        $productAllQuantity = $query->count();

        // get query products paginate
        $products = $query->withTranslation(app()->getLocale())->paginate($quantity);
        $links = $products->links();

        $products = $products->translate();

        $partner = Helper::translation($partner);

        $breadcrumbs->addItem(new LinkItem($partner->name, $partner->url, LinkItem::STATUS_INACTIVE));

        return view('partner', compact('page', 'breadcrumbs', 'products', 'productAllQuantity', 'partner', 'links', 'quantity', 'quantityPerPage', 'sorts', 'sortCurrent'));
    }

}
