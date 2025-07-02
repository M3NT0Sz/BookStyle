<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::get();
        $books = [];
        foreach ($cart as $bookId => $quantity) {
            $book = Book::find($bookId);
            if ($book) {
                $book['quantity'] = $quantity;
                $books[] = $book;
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
        \App\Models\Cart::add($bookId, $quantity);
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
}
