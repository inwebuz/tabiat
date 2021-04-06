<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * show products per page values
     */
    public $quantityPerPage = [24, 60, 120];
    public $sorts = ['created_at-desc', 'price-asc', 'price-desc'];

    /**
     * show products per page values
     */
    public $filters = ['special', 'popular', 'new'];

    public function index()
    {
        $breadcrumbs = new Breadcrumbs();
        $page = Helper::translation(Page::findOrFail(5));
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $categories = Category::active()->whereNull('parent_id')->with('translations')->get()->translate();

        return view('categories', compact('page', 'breadcrumbs', 'categories'));
    }

    public function view(Request $request, Category $category)
    {
        $locale = app()->getLocale();
        $breadcrumbs = new Breadcrumbs();

        // $page = Helper::translation(Page::findOrFail(5));

        // $breadcrumbs->addItem(new LinkItem($page->name, $page->url));

        // quantity per page
        $quantityPerPage = $this->quantityPerPage;
        $quantity = request('quantity', $this->quantityPerPage[0]);
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

        // temp
        $sort = 'order';
        $order = 'asc';

        // min and max price (per month)
        $prices = [];
        $categoryPrices = [];
        $requestPrices = $request->input('price', []);
        $prices['from'] = $categoryPrices['from'] = isset($requestPrices['from']) ? $requestPrices['from'] : null;
        $prices['to'] = $categoryPrices['to'] = isset($requestPrices['to']) ? $requestPrices['to'] : null;

        // filters
        $filter = $request->input('filter', '');
        if (!empty($filter) && !in_array($filter, $this->filters)) {
            $filter = '';
        }

        // brands
        $brands = $request->input('brand', []);
        if (!empty($brands) && !is_array($brands)) {
            $brands = [];
        }

        // attributes
        $attributes = $request->input('attribute', []);
        if (!empty($attributes) && !is_array($attributes)) {
            $attributes = [];
        }

        $query = $category->products()->orderBy('products.' . $sort, $order)->active();

        // apply filters
        if ($filter) {
            $query->where('is_' . $filter, constant('\App\Product::' . mb_strtoupper($filter) . '_ACTIVE'));
        }

        // category brands, attributes and attribute values (before attribute and brand filter applied)
        $queryClone = clone $query;
        $categoryBrands = $category->allBrands($queryClone);
        $queryClone = clone $query;
        $categoryAttributes = $category->allAttributes($queryClone);

        // get min max prices
        $queryClone = clone $query;
        $categoryPrices['min'] = $queryClone->select('price')->min('price');
        $categoryPrices['max'] = $queryClone->select('price')->max('price');

        // apply brands
        if ($brands) {
            $query->whereIn('products.brand_id', $brands);
        }

        // apply attributes
        if ($attributes) {
            foreach($attributes as $key => $values) {
                $attributeValueIds = [];
                $values = array_map('intval', $values);
                $attributeValueIds = array_merge($attributeValueIds, $values);
                if ($attributeValueIds) {
                    $query->whereIn('products.id', function($q1) use ($attributeValueIds) {
                        $q1->select('products.id')->from('products')->whereIn('products.id', function($q2) use ($attributeValueIds) {
                            $q2->select('product_id')->from('attribute_value_product')->whereIn('attribute_value_id', $attributeValueIds);
                        });
                    });
                }
            }
        }

        // apply prices
        if (isset($prices['from']) && isset($prices['to'])) {
            if ($prices['from'] > $prices['to']) {
                $tmp = $prices['from'];
                $prices['from'] = $prices['to'];
                $prices['to'] = $tmp;
            }
            $query->where('products.price', '>=', $prices['from'])
                  ->where('products.price', '<=', $prices['to']);
        }

        $productAllQuantity = $query->count();

        // get query products paginate
        $products = $query->with('translations')->with(['activeReviews' => function($q1){
            $q1->active();
        }])->paginate($quantity);

        $appends = ['quantity' => $quantity, 'sort' => $sortCurrent, 'attribute' => $attributes, 'brand' => $brands];
        if (isset($prices['from']) && isset($prices['to'])) {
            $appends['price'] = $prices;
        }
        $links = $products->appends($appends)->links();

        $products = $products->translate();

        if($category->parent) {
            $parent = Helper::translation($category->parent);
            $breadcrumbs->addItem(new LinkItem($parent->name, $parent->url));
        }

        // categories
        // $categories = Category::active()->parents()->with('children.children.children')->get();
        // $siblingCategories = Category::active()->where('parent_id', $category->parent_id)->get();
        $subcategories = Category::active()->where('parent_id', $category->id)->withTranslation($locale)->with(['children' => function($query) use ($locale) {
            $query->withTranslation($locale);
        }])->get();
        if ($subcategories) {
            $subcategories = $subcategories->translate();
        }

        // current and its parent category ids
        $activeCategoryIds = Helper::activeCategories($category);

        $category = Helper::translation($category);

        $new_products = Product::active()->latest()->take(4)->get();

        $banner = Helper::banner('sidebar_1');
        if ($banner) {
            $banner = $banner->translate();
        }

        $breadcrumbs->addItem(new LinkItem($category->name, $category->url, LinkItem::STATUS_INACTIVE));

        return view('category', compact('breadcrumbs', 'banner', 'products', 'productAllQuantity', 'category', 'new_products', 'activeCategoryIds', 'subcategories', 'links', 'brands', 'attributes', 'quantity', 'quantityPerPage', 'sorts', 'sortCurrent', 'categoryBrands', 'categoryAttributes', 'categoryPrices', 'prices'));
    }

}
