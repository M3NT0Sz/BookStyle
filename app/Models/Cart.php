<?php
namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class Cart
{
    /**
     * Adicionar item ao carrinho
     */
    public static function add($bookId, $quantity = 1)
    {
        if (!Auth::check()) {
            // Fallback para sessão se usuário não estiver logado
            return self::addToSession($bookId, $quantity);
        }

        $userId = Auth::id();
        
        // Verificar se o item já existe no carrinho
        $cartItem = CartItem::where('user_id', $userId)
                           ->where('book_id', $bookId)
                           ->first();

        if ($cartItem) {
            // Se existe, incrementar quantidade
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Se não existe, criar novo item
            CartItem::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Remover item do carrinho
     */
    public static function remove($bookId)
    {
        if (!Auth::check()) {
            return self::removeFromSession($bookId);
        }

        CartItem::where('user_id', Auth::id())
               ->where('book_id', $bookId)
               ->delete();
    }

    /**
     * Limpar carrinho
     */
    public static function clear()
    {
        if (!Auth::check()) {
            return self::clearSession();
        }

        CartItem::where('user_id', Auth::id())->delete();
    }

    /**
     * Obter itens do carrinho
     */
    public static function get()
    {
        if (!Auth::check()) {
            return self::getFromSession();
        }

        $cartItems = CartItem::with('book')
                            ->where('user_id', Auth::id())
                            ->get();

        // Retornar no formato antigo para compatibilidade
        $cart = [];
        foreach ($cartItems as $item) {
            $cart[$item->book_id] = $item->quantity;
        }

        return $cart;
    }

    /**
     * Obter itens do carrinho com detalhes
     */
    public static function getWithDetails(): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        return CartItem::with('book')
                      ->where('user_id', Auth::id())
                      ->get();
    }

    /**
     * Contar itens no carrinho
     */
    public static function count(): int
    {
        if (!Auth::check()) {
            $cart = self::getFromSession();
            return array_sum($cart);
        }

        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }

    /**
     * Calcular total do carrinho
     */
    public static function total(): float
    {
        if (!Auth::check()) {
            return 0;
        }

        $total = 0;
        $cartItems = CartItem::with('book')->where('user_id', Auth::id())->get();
        
        foreach ($cartItems as $item) {
            $total += $item->quantity * $item->book->price;
        }

        return $total;
    }

    /**
     * Atualizar quantidade de um item
     */
    public static function updateQuantity($bookId, $quantity)
    {
        if (!Auth::check()) {
            return self::updateSessionQuantity($bookId, $quantity);
        }

        if ($quantity <= 0) {
            return self::remove($bookId);
        }

        CartItem::where('user_id', Auth::id())
               ->where('book_id', $bookId)
               ->update(['quantity' => $quantity]);
    }

    /**
     * Migrar carrinho da sessão para o banco quando usuário fizer login
     */
    public static function migrateFromSession()
    {
        if (!Auth::check()) {
            return;
        }

        $sessionCart = session('cart', []);
        
        foreach ($sessionCart as $bookId => $quantity) {
            if (is_numeric($bookId) && is_numeric($quantity)) {
                self::add($bookId, $quantity);
            }
        }

        // Limpar carrinho da sessão após migração
        session()->forget('cart');
    }

    // ====== MÉTODOS DE FALLBACK PARA SESSÃO ======

    private static function addToSession($productId, $quantity = 1)
    {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        session(['cart' => $cart]);
    }

    private static function removeFromSession($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }

    private static function clearSession()
    {
        session(['cart' => []]);
    }

    private static function getFromSession()
    {
        return session('cart', []);
    }

    private static function updateSessionQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            return self::removeFromSession($productId);
        }

        $cart = session('cart', []);
        $cart[$productId] = $quantity;
        session(['cart' => $cart]);
    }
}
