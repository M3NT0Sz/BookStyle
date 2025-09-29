<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Para usuários logados, usar dados do banco - FRESH QUERY
            $cartItems = CartItem::with('book')
                                ->where('user_id', Auth::id())
                                ->get();
            $books = [];
            
            foreach ($cartItems as $item) {
                $book = $item->book->toArray();
                $book['quantity'] = $item->quantity;
                $books[] = $book;
            }
        } else {
            // Para usuários não logados, usar sessão (compatibilidade)
            $cart = Cart::get();
            $books = [];
            foreach ($cart as $bookId => $quantity) {
                $book = Book::findBook($bookId);
                if ($book) {
                    // $book já é um array do PDO, não precisa do toArray()
                    $book['quantity'] = $quantity;
                    $books[] = $book;
                }
            }
        }
        
        return view('cart.index', compact('books'));
    }

    public function add(Request $request, $bookId)
    {
        \Log::info('Método add do carrinho chamado', [
            'bookId_param' => $bookId,
            'bookId_input' => $request->input('book_id'),
            'quantity' => $request->input('quantity'),
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id()
        ]);
        
        $quantity = $request->input('quantity', 1);
        $couponCode = $request->input('coupon_code');
        $discount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::findByCode($couponCode);
            if ($coupon) {
                $cart = session('cart', []);
                $cart['coupon'] = $coupon;
                session(['cart' => $cart]);
            }
        }
        
        Cart::add($bookId, $quantity);
        
        // Se for requisição AJAX, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Livro adicionado ao carrinho!' . ($coupon ? ' Cupom aplicado!' : ''),
                'cart_count' => Cart::count()
            ]);
        }
        
        return redirect()->back()->with('success', 'Livro adicionado ao carrinho!' . ($coupon ? ' Cupom aplicado!' : ''));
    }

    public function remove($bookId)
    {
        Cart::remove($bookId);
        return redirect()->route('cart.index')->with('success', 'Livro removido do carrinho!');
    }

    public function clear()
    {
        Cart::clear();
        return redirect()->route('cart.index')->with('success', 'Carrinho esvaziado!');
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        if (!$code) {
            return redirect()->back()->with('coupon_error', 'Informe o código do cupom.');
        }
        $coupon = \App\Models\Coupon::findByCode($code);
        if (!$coupon) {
            return redirect()->back()->with('coupon_error', 'Cupom inválido.');
        }
        if (isset($coupon['expires_at']) && $coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) {
            return redirect()->back()->with('coupon_error', 'Cupom expirado.');
        }
        $cart = session('cart', []);
        $cart['coupon'] = [
            'code' => $coupon['code'],
            'discount' => $coupon['discount'],
            'type' => $coupon['type'],
        ];
        session(['cart' => $cart]);
        return redirect()->back()->with('coupon_success', 'Cupom aplicado com sucesso!');
    }

    /**
     * Retornar contagem de itens no carrinho (para AJAX)
     */
    public function count()
    {
        return response()->json([
            'count' => Cart::count()
        ]);
    }

    /**
     * Atualizar quantidade de um item
     */
    public function updateQuantity(Request $request, $bookId)
    {
        $quantity = $request->input('quantity', 1);
        
        Cart::updateQuantity($bookId, $quantity);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Quantidade atualizada!',
                'cart_count' => Cart::count()
            ]);
        }
        
        return redirect()->back()->with('success', 'Quantidade atualizada!');
    }
}
