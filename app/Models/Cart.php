<?php
namespace App\Models;

class Cart
{
    public static function add($productId, $quantity = 1)
    {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        session(['cart' => $cart]);
    }

    public static function remove($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }

    public static function clear()
    {
        session(['cart' => []]);
    }

    public static function get()
    {
        return session('cart', []);
    }
}
