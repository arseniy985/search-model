<?php

namespace App\Http\Helpers;

use Exception;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Arr;

class Cart
{
    public static function getCartItemsCount(): int
    {
        $request = request();
        $user = $request->user();

        if ($user) {
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce(
                $cartItems, // array - which is the array to be reduced.
                fn ($carry, $item) => $item !== null && isset($item['quantity']) ? $carry + $item['quantity'] : $carry, // for each item in cartItems.
                0 // Initial value for carry.
            );
        }
    }

    public static function getCartItems()
    {
        $request = request();
        $user = $request->user();

        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(
                fn ($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems()
    {
        $request = request();
        return json_decode($request->cookie('cart_items', '[]'), true);
    }

    public static function getCountFromItems($cartItems)
    {
        return array_reduce(
            $cartItems, // array - which is the array to be reduced.
            fn ($carry, $item) => $item !== null && isset($item['quantity']) ? $carry + $item['quantity'] : $carry, // for each item in cartItems.
            0 // Initial value for carry.
        );
    }

    public static function moveCartItemsIntoDB()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $dbCartItems = CartItem::where(['user_id' => $request->user()->id])->get()->keyBy('product_id');
        $newCartItems = [];

        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }

            $newCartItems[] = [
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        if (Product::exists()) {
            if (!empty($newCartItems)) {
                CartItem::insert($newCartItems);
            }
        }
    }

    public static function getProductsAndCartItems()
    {
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');

        return [$products, $cartItems];
    }
}
