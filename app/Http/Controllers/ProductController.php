<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeValue;
use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Helpers\Rating;
use App\ProductVariant;
use App\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\SchemaOrg\AggregateRating;
use Spatie\SchemaOrg\Schema;

class ProductController extends Controller
{

    /**
     * show products per page values
     */
    public $quantityPerPage = [12, 60, 120];
    public $sorts = ['created_at-desc', 'price-asc', 'price-desc'];

    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        //
    }

    public function view(Product $product)
    {
        $locale = app()->getLocale();

        Helper::checkModelActive($product);
        // $page = Page::findOrFail(4);
        $breadcrumbs = new Breadcrumbs();
        //$breadcrumbs->addItem(new LinkItem($page->name, $page->url));

        $brand = $product->brand;
        if (!empty($brand)) {
            $brand = Helper::translation($brand);
        }
        // $shop = $product->shop;
        // if($shop) {
        //     $shop = $shop->translate();
        // }
        $similar_products = collect();

        $subcategoriesParentCategoryID = null;
        $category = $product->categories->first();
        if (!empty($category)) {
            $subcategoriesParentCategoryID = $category->id;
            if (!empty($category->parent)) {
                $parent = Helper::translation($category->parent);
                $subcategoriesParentCategoryID = $parent->id;
                $breadcrumbs->addItem(new LinkItem($parent->name, $parent->url));
            }
            $similar_products = $category->products()->active()->where('products.id', '!=', $product->id)->latest()->take(12)->get();
            $similar_products = $similar_products->translate();

            $category = Helper::translation($category);
            $breadcrumbs->addItem(new LinkItem($category->name, $category->url));
        }

        $subcategories = collect();
        if ($category) {
            $subcategories = Category::active()->where('parent_id', $subcategoriesParentCategoryID)->withTranslation($locale)->with(['children' => function($query) use ($locale) {
                $query->withTranslation($locale);
            }])->get();
            if ($subcategories) {
                $subcategories = $subcategories->translate();
            }
        }

        $reviews = $product->reviews()->active()->latest()->take(20)->get();

        $attributeValueIds = $product->attributeValuesIds();
        $attributes = $product->attributesOrdered()->withTranslation($locale)->with(['attributeValues' => function($q1) use ($attributeValueIds, $locale) {
            $q1->whereIn('id', $attributeValueIds)->withTranslation($locale);
        }])->get()->translate();

//        $prev = Product::active()->similar($product)->where('id', '<', $product->id)->orderBy('id','desc')->first();
//        if ($prev) {
//            $prev = $prev->translate();
//        }
//        $next = Product::active()->similar($product)->where('id', '>', $product->id)->orderBy('id','asc')->first();
//        if ($next) {
//            $next = $next->translate();
//        }

        $productVariants = $product->productVariants()->active()->get();
        $productVariantsAttributes = $product->variantAttributesWithValues()->get();

        if (!$productVariants->isEmpty()) {
            $productVariantsAttributeValueIds = collect();
            foreach($productVariants as $productVariant) {
                $productVariantsAttributeValueIds = $productVariantsAttributeValueIds->merge($productVariant->attributeValueIds());
            }
            $productVariantsAttributeValueIds = $productVariantsAttributeValueIds->unique();
            foreach($productVariantsAttributes as $productVariantAttribute) {
                foreach ($productVariantAttribute->attributeValues as $attributeValueKey => $attributeValue) {
                    if (!$productVariantsAttributeValueIds->contains($attributeValue->id)) {
                        $productVariantAttribute->attributeValues->forget($attributeValueKey);
                    }
                }
            }
        }

        $product = Helper::translation($product);
        // $breadcrumbs->addItem(new LinkItem($product->name, $product->url, LinkItem::STATUS_INACTIVE));

        // SEO templates
        // $product = Helper::seoTemplate($product, 'product', ['name' => $product->name]);

        $microdata = Schema::product();
        $microdata->name($product->name);
        $aggregateRating = new AggregateRating();
        $aggregateRating->worstRating(1)->bestRating(5)->ratingCount($product->rating_count)->ratingValue($product->rating_avg);
        $microdata->aggregateRating($aggregateRating);
        $microdata = $microdata->toScript();

        $banner = Helper::banner('sidebar_1');
        if ($banner) {
            $banner = $banner->translate();
        }

        return view('product.view', compact('breadcrumbs', 'banner', 'subcategories', 'product', 'productVariants', 'productVariantsAttributes', 'brand', 'category', 'attributes', 'reviews', 'similar_products', 'microdata'));
    }

    /* resources */
    public function show(Product $product)
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.product'), route('products.show', $product->id), LinkItem::STATUS_INACTIVE));
        return view('product.show', compact('breadcrumbs', 'product'));
    }

    public function create()
    {
        $product = new Product();
        $categories = Category::active()->orderBy('name')->get();
        $category_id = request()->input('category_id', '');
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.add'), route('products.create'), LinkItem::STATUS_INACTIVE));
        return view('product.create', compact('breadcrumbs', 'product', 'categories', 'category_id'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $user = auth()->user();
        $shop = $user->shops()->first();
        if (!$shop) {
            $shop = Shop::craete([
                'user_id' => $user->id,
                'name' => 'Shop',
            ]);
        }

        // set additional data
        $data['in_stock'] = $request->has('in_stock') ? 1 : 0;
        $data['slug'] = Str::slug($data['name']);
        $data['status'] = Product::STATUS_PENDING;
        $data['unique_code'] = uniqid();
        $data['user_id'] = $user->id;
        $data['shop_id'] = $shop->id;

        $product = Product::create($data);

        Helper::storeImage($product, 'image', 'products', Product::$imgSizes);

        Session::flash('message', __('main.data_saved') . '. ' . __('main.pending_moderator_review'));
        return redirect()->route('profile.products');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        $category_id = request()->input('category_id', '');
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.edit'), route('products.edit', $product->id), LinkItem::STATUS_INACTIVE));
        return view('product.edit', compact('breadcrumbs', 'product', 'categories', 'category_id'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request);

        // set additional data
        $data['in_stock'] = $request->has('in_stock') ? 1 : 0;
        $data['status'] = Product::STATUS_PENDING;

        $product->update($data);

        Helper::storeImage($product, 'image', 'products', Product::$imgSizes);

        Session::flash('message', __('main.data_saved') . '. ' . __('main.pending_moderator_review'));
        return redirect()->route('profile.products');
    }

    public function destroy(Request $request, Product $product)
    {
        // TODO: delete image
        // Helper::deleteImage($product, 'image', Product::$imgSizes);

        $product->delete();


        Session::flash('message', __('main.data_deleted'));
        return redirect()->route('profile.products');
    }

    protected function validatedData(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:190',
            'price' => 'required|numeric|max:1000000000',
            'sale_price' => '',
            'description' => 'max:1000',
            'image' => 'sometimes|image|max:1000',
            'body' => '',
        ]);
        $data['sale_price'] = (float)$data['sale_price'];

        return $data;
    }


    public function attributesEdit(Product $product)
    {
        $this->authorize('update', $product);

        $attributes = Attribute::all();
        $attributeValueIds = $product->attributeValues()->pluck('attribute_value_id')->toArray();

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.edit'), route('products.edit', $product->id)));
        $breadcrumbs->addItem(new LinkItem(__('main.attributes'), route('products.attributes.edit', $product->id), LinkItem::STATUS_INACTIVE));

        return view('product.edit-attributes', compact('breadcrumbs', 'product', 'attributes', 'attributeValueIds'));
    }

    public function attributesUpdate(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $attributes = $request->input('attributes', []);

        // product attributes
        $productAttributes = [];
        foreach($attributes as $key => $attribute) {
            $productAttributes[$key] = [
                'used_for_variations' => (isset($attribute['used_for_variations']) && $attribute['used_for_variations']) ? 1 : 0,
            ];
        }
        $product->attributes()->sync($productAttributes);

        // product attribute values
        $productAttributeValues = [];
        foreach($attributes as $attribute) {
            $productAttributeValues = array_merge($productAttributeValues, $attribute['values']);
        }
        $product->attributeValues()->sync($productAttributeValues);

        return redirect()->back()->with([
            'message'    => __('main.attributes_saved'),
            'alert-type' => 'success',
        ]);
    }

    public function variants(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.edit'), route('products.edit', $product->id)));
        $breadcrumbs->addItem(new LinkItem(__('main.variants'), route('products.variants', $product->id), LinkItem::STATUS_INACTIVE));

        $variants = $product->productVariants;
        return view('product.variants.browse', compact('breadcrumbs', 'product', 'variants'));
    }

    public function variantsCreate(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.edit'), route('products.edit', $product->id)));
        $breadcrumbs->addItem(new LinkItem(__('main.variants'), route('products.variants', $product->id)));

        $productVariantAttributes = $product->variantAttributesWithValues()->get();

        $variant = new ProductVariant();
        $variantAttributeValueIds = [];

        return view('product.variants.create', compact('breadcrumbs', 'product', 'variant', 'productVariantAttributes', 'variantAttributeValueIds'));
    }

    public function variantsStore(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        return $this->variantsSave($request, $product);
    }

    public function variantsEdit(Request $request, Product $product, ProductVariant $variant)
    {
        $this->authorize('update', $product);

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.profile'), route('profile.show')));
        $breadcrumbs->addItem(new LinkItem(__('main.products'), route('profile.products')));
        $breadcrumbs->addItem(new LinkItem(__('main.edit'), route('products.edit', $product->id)));
        $breadcrumbs->addItem(new LinkItem(__('main.variants'), route('products.variants', $product->id)));

        $productVariantAttributes = $product->variantAttributesWithValues()->get();

        $variantAttributeValueIds = $variant->attributeValueIds();

        return view('product.variants.edit', compact('breadcrumbs', 'product', 'variant', 'productVariantAttributes', 'variantAttributeValueIds'));
    }

    public function variantsUpdate(Request $request, Product $product, ProductVariant $variant)
    {
        $this->authorize('update', $product);

        return $this->variantsSave($request, $product);
    }

    private function variantsSave(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validation = [
            'price' => 'required|numeric',
        ];
        $attributes = $product->variantAttributes()->get();
        $productAttributeValueIds = $product->attributeValuesIds()->toArray();

        foreach($attributes as $attribute) {
            $validation['attributes.attribute_' . $attribute->id] = 'required|in:' . implode(',', $productAttributeValueIds);
        }
        $request->validate($validation, [
            '*.required' => 'Обязательное поле',
        ]);

        $dataCheck = [];
        $data = [];

        $attributes = $request->input('attributes');

        $dataCheck['product_id'] = $product->id;
        $dataCheck['combination'] = json_encode(array_values($attributes));

        $attributeValueNames = AttributeValue::whereIn('id', $attributes)->pluck('name');
        $data['name'] = $product->name . ' | ' . $attributeValueNames->implode(', ');

        $data['price'] = $request->input('price');
        $data['sale_price'] = $request->input('sale_price');

        $data['in_stock'] = $request->has('in_stock') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;

        // TODO: delete old img and save new img

        ProductVariant::updateOrCreate($dataCheck, $data);

        return redirect()->route('products.variants', $product->id)->with([
            'message'    => __('main.variant_saved'),
            'alert-type' => 'success',
        ]);
    }

    public function variantsDestroy(Request $request, Product $product, ProductVariant $variant)
    {
        $this->authorize('delete', $product);

        // TODO: delete img

        $variant->delete();
        return redirect()->back()->with([
            'message'    => __('main.variant_deleted'),
            'alert-type' => 'success',
        ]);
    }

    public function featured(Request $request)
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Helper::translation(Page::where('slug', 'featured')->firstOrFail());
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

        $query = Product::featured()->active()->orderBy('products.' . $sort, $order);

        $productAllQuantity = $query->count();

        // get query products paginate
        $products = $query->withTranslation(app()->getLocale())->paginate($quantity);
        $links = $products->links();

        $products = $products->translate();

        return view('featured', compact('page', 'breadcrumbs', 'products', 'productAllQuantity', 'links', 'quantity', 'quantityPerPage', 'sorts', 'sortCurrent'));
    }

    public function sale(Request $request)
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Helper::translation(Page::where('slug', 'sale')->firstOrFail());
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

        $query = Product::where('sale_price', '>', 0)->whereColumn('price', '>', 'sale_price')->active()->orderBy('products.' . $sort, $order);

        $productAllQuantity = $query->count();

        // get query products paginate
        $products = $query->withTranslation(app()->getLocale())->paginate($quantity);
        $links = $products->links();

        $products = $products->translate();

        return view('sale', compact('page', 'breadcrumbs', 'products', 'productAllQuantity', 'links', 'quantity', 'quantityPerPage', 'sorts', 'sortCurrent'));
    }

    public function catalog(Request $request)
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Helper::translation(Page::findOrFail(4));
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        // quantity per page
        $quantityPerPage = $this->quantityPerPage;
        $quantity = $request->input('quantity', $this->quantityPerPage[0]);
        if (!in_array($quantity, $this->quantityPerPage)) {
            $quantity = $this->quantityPerPage[0];
        }

        $products = Product::active()->latest()->withTranslation(app()->getLocale())->paginate($quantity);

        // get query products paginate
        $links = $products->links();

        if (!$products->isEmpty()) {
            $products = $products->translate();
        }

        return view('catalog', compact('page', 'breadcrumbs', 'products', 'links', 'quantity'));
    }
}
