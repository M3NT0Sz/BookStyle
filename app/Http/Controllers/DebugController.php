<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    /**
     * Verificar status do carrinho para debug
     */
    public function checkCart()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuário não logado']);
        }

        $userId = Auth::id();
        
        // Verificar itens no banco
        $cartItems = CartItem::where('user_id', $userId)->get();
        
        // Verificar sessão
        $sessionCart = session('cart', []);
        
        // Contar usando método Cart
        $countFromMethod = Cart::count();
        
        return response()->json([
            'user_id' => $userId,
            'cart_items_count' => $cartItems->count(),
            'cart_items' => $cartItems->toArray(),
            'session_cart' => $sessionCart,
            'count_from_method' => $countFromMethod,
            'total_from_method' => Cart::total()
        ]);
    }

    /**
     * Forçar limpeza do carrinho para debug
     */
    public function clearCart()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuário não logado']);
        }

        $userId = Auth::id();
        
        // Limpeza forçada
        $deleted = CartItem::where('user_id', $userId)->delete();
        session()->forget('cart');
        session()->save();
        
        return response()->json([
            'message' => 'Carrinho limpo',
            'items_deleted' => $deleted,
            'user_id' => $userId
        ]);
    }
}