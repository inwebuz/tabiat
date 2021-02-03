<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Order;
use App\Page;
use App\Product;
use App\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.cart'), route('cart.index')));
        $cart = app('cart');
        $cartItems = $cart->getContent()->sortBy('id');

        return view('cart', compact('breadcrumbs', 'cart', 'cartItems'));
    }

    public function checkout()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.cart'), route('cart.index')));
        $breadcrumbs->addItem(new LinkItem(__('main.checkout'), route('cart.checkout')));
        $cart = app('cart');
        $cartItems = $cart->getContent()->sortBy('id');
        $publicOfferPage = Page::find(7);

        $orderTypes = Order::types();
        $communicationMethods = Order::communicationMethods();

        return view('checkout', compact('breadcrumbs', 'cart', 'cartItems', 'publicOfferPage', 'orderTypes', 'communicationMethods'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer'
        ]);

        $data['associatedModel'] = Product::findOrFail($data['id']);
        $variantId = $request->input('variantId', '');
        if ($variantId) {
            $data['associatedModel'] = ProductVariant::findOrFail($variantId);
            $data['id'] .= '-' . $variantId;
        }

        if (
            $data['associatedModel']->current_price != $data['price'] ||
            trim($data['associatedModel']->name) != trim($data['name'])
        ) {
            abort(400);
        }

        // Log::info($data);

        app('cart')->add($data);

        return response([
            'cart' => $this->getCartInfo(app('cart')),
            'message' => __('main.product_added_to_cart'),
        ], 201);
    }

    public function debug(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer'
        ]);
        $data['associatedModel'] = Product::findOrFail($request->input('id'));

        $cart = app('cart')->add($data);
        $cart = $cart->getContent()->toArray();
        dd($cart);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'required|integer'
        ]);

        app('cart')->update($data['id'], [
            'quantity' => [
                'relative' => false,
                'value' => $data['quantity'],
            ],
        ]);

        $item = app('cart')->get($data['id']);

        $lineTotal = $item->getPriceSum();

        return response([
            'cart' => $this->getCartInfo(app('cart')),
            'lineTotal' => $lineTotal,
            'lineTotalFormatted' => Helper::formatPrice($lineTotal),
            'message' => __('main.cart_updated')
        ], 200);
    }

    public function delete($id)
    {
        app('cart')->remove($id);

        return response(array(
            'cart' => $this->getCartInfo(app('cart')),
            'message' => __('main.product_removed_from_cart')
        ), 200);
    }

    public function addCondition()
    {
        $v = validator(request()->all(), [
            'name' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
            'value' => 'required|string',
        ]);

        if ($v->fails()) {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => $v->errors()->first()
            ), 400, []);
        }

        $name = request('name');
        $type = request('type');
        $target = request('target');
        $value = request('value');

        $cartCondition = new CartCondition([
            'name' => $name,
            'type' => $type,
            'target' => $target, // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $value,
            'attributes' => array()
        ]);

        app('cart')->condition($cartCondition);

        return response(array(
            'success' => true,
            'data' => $cartCondition,
            'message' => "condition added."
        ), 201, []);
    }

    public function clearCartConditions()
    {
        app('cart')->clearCartConditions();

        return response(array(
            'success' => true,
            'data' => [],
            'message' => "cart conditions cleared."
        ), 200, []);
    }

    private function getCartInfo($cart)
    {
        $subtotal = $cart->getSubtotal();
        $total = $cart->getTotal();
        return [
            'quantity' => $cart->getTotalQuantity(),
            'subtotal' => $subtotal,
            'subtotalFormatted' => Helper::formatPrice($subtotal),
            'total' => $total,
            'totalFormatted' => Helper::formatPrice($total),
        ];
    }
}
