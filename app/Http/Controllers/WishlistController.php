<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.wishlist'), route('wishlist.index')));
        $wishlist = app('wishlist');
        $wishlistItems = $wishlist->getContent()->sortBy('id');

        return view('wishlist', compact('breadcrumbs', 'wishlist', 'wishlistItems'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required',
            'price' => 'required|numeric|min:0'
        ]);

        $data['quantity'] = 1;
        $data['associatedModel'] = Product::findOrFail($request->input('id'));

        if (
            $data['associatedModel']->current_price != $data['price'] ||
            trim($data['associatedModel']->name) != trim($data['name'])
        ) {
            abort(400);
        }

        app('wishlist')->add($data);

        return response([
            'wishlist' => $this->getWishlistInfo(app('wishlist')),
            'message' => __('main.product_added_to_wishlist'),
        ], 201);
    }

    public function delete($id)
    {
        app('wishlist')->remove($id);

        return response(array(
            'wishlist' => $this->getWishlistInfo(app('wishlist')),
            'message' => __('main.product_removed_from_wishlist')
        ), 200);
    }

    private function getWishlistInfo($wishlist)
    {
        $subtotal = $wishlist->getSubtotal();
        $total = $wishlist->getTotal();
        return [
            'quantity' => $wishlist->getTotalQuantity(),
            'subtotal' => $subtotal,
            'subtotalFormatted' => Helper::formatPrice($subtotal),
            'total' => $total,
            'totalFormatted' => Helper::formatPrice($total),
        ];
    }
}
