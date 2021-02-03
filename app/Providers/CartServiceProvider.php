<?php

namespace App\Providers;

use App\Product;
use App\ProductVariant;
use App\Storages\CartStorage;
use Darryldecode\Cart\Cart;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class CartServiceProvider extends ServiceProvider
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
        $this->app->singleton('cart', function($app)
        {
            $storage = new CartStorage();
            $events = $app['events'];
            $instanceName = 'cart';

            if (auth()->check()) {
                // user cart
                $sessionKey = auth()->user()->id;
            } else {
                // cookie cart
                $sessionKey = request()->cookie('cart_session_key', Str::random(30));
                if (!Cookie::has('cart_session_key')) {
                    Cookie::queue('cart_session_key', $sessionKey, 1440 * 30);
                }
            }

            $config = config('shopping_cart');

            // new cart
            $cart = new Cart(
                $storage,
                $events,
                $instanceName,
                $sessionKey,
                $config
            );

            // if user logged in check cookie cart
            if (auth()->check() && Cookie::has('cart_session_key')) {
                $oldCartSessionKey = Cookie::get('cart_session_key');
                $oldCart = new Cart(
                    $storage,
                    $events,
                    $instanceName,
                    $oldCartSessionKey,
                    $config
                );
                // add cookie cart items to user cart
                foreach($oldCart->getContent() as $oldCartItem) {
                    $oldCartItem = $oldCartItem->toArray();
                    $oldCartItem['associatedModel'] = Product::find($oldCartItem['id']);
                    if ($oldCartItem['associatedModel']) {
                        $cart->add($oldCartItem);
                    }
                }
                // clear cookie cart
                $oldCart->clear();
            }

            // update cart items
            foreach($cart->getContent() as $cartItem) {
                $id = $cartItem->id;
                $isVariant = false;
                if (strpos($cartItem->id, '-') !== false) {
                    $isVariant = true;
                    $getId = explode('-', $cartItem->id);
                    $id = $getId[1];
                }
                $product = $isVariant ? ProductVariant::find($id) : Product::find($id);
                if(!$product) {
                    $cart->remove($cartItem->id);
                    continue;
                }
                $cart->update($cartItem->id, [
                    'price' => $product->current_price,
                    'associatedModel' => $product,
                ]);
            }

            return $cart;
        });
    }
}
