<?php

namespace App\Storages;

use App\Wishlist;
use Darryldecode\Cart\CartCollection;

class WishlistStorage
{
    public function has($key)
    {
        return Wishlist::find($key);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return new CartCollection(Wishlist::find($key)->cart_data);
        } else {
            return [];
        }
    }

    public function put($key, $value)
    {
        if ($row = Wishlist::find($key)) {
            // update
            $row->cart_data = $value;
            $row->save();
        } else {
            Wishlist::create([
                'id' => $key,
                'cart_data' => $value
            ]);
        }
    }
}
