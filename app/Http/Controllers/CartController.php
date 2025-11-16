<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Services\SmartCouponService;
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
            
            // Obter sugestões de cupons (já filtra cupons automáticos em cooldown)
            $suggestedCoupons = SmartCouponService::getSuggestedCoupons(Auth::id(), $books);
            
            // Obter cupons disponíveis
            $availableCoupons = Coupon::getAvailableCouponsForUser(Auth::id());
            
            
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
            
            $suggestedCoupons = [];
            $availableCoupons = [];
        }
        
        return view('cart.index', compact('books', 'suggestedCoupons', 'availableCoupons'));
    }

    public function add(Request $request, $bookId)
    {
        try {
            \Log::info('Método add do carrinho chamado', [
                'bookId_param' => $bookId,
                'bookId_input' => $request->input('book_id'),
                'quantity' => $request->input('quantity'),
                'user_authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson(),
                'expects_json' => $request->expectsJson()
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
            
            // Se for requisição AJAX ou espera JSON, retornar JSON
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Livro adicionado ao carrinho!' . ($coupon ? ' Cupom aplicado!' : ''),
                    'cart_count' => Cart::count()
                ]);
            }
            
            return redirect()->route('cart.index')->with('success', 'Livro adicionado ao carrinho!' . ($coupon ? ' Cupom aplicado!' : ''));
            
        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar ao carrinho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao adicionar ao carrinho: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erro ao adicionar ao carrinho.');
        }
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
        
        // Verificar se o carrinho está vazio
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->count();
            if ($cartCount == 0) {
                return redirect()->back()->with('coupon_error', 'Adicione produtos ao carrinho antes de aplicar um cupom.');
            }
        } else {
            $cart = session('cart', []);
            $cartItems = array_filter($cart, fn($key) => $key !== 'coupon', ARRAY_FILTER_USE_KEY);
            if (empty($cartItems)) {
                return redirect()->back()->with('coupon_error', 'Adicione produtos ao carrinho antes de aplicar um cupom.');
            }
        }
        
        $coupon = Coupon::findByCode($code);
        if (!$coupon) {
            return redirect()->back()->with('coupon_error', 'Cupom inválido.');
        }
        
        // Obter itens do carrinho (usuário logado ou sessão)
        $cartBooks = [];
        
        if (Auth::check()) {
            // Para usuários logados, buscar do banco
            $cartItems = CartItem::with('book')->where('user_id', Auth::id())->get();
            foreach ($cartItems as $item) {
                if ($item->book) {
                    $cartBooks[] = [
                        'id' => $item->book->id,
                        'name' => $item->book->title,
                        'price' => $item->book->price,
                        'quantity' => $item->quantity,
                        'genre' => $item->book->genre ?? null
                    ];
                }
            }
        } else {
            // Para usuários não logados, buscar da sessão
            $cart = session('cart', []);
            foreach ($cart as $bookId => $item) {
                if ($bookId !== 'coupon' && is_array($item)) {
                    $cartBooks[] = $item;
                }
            }
        }
        
        if (empty($cartBooks)) {
            return redirect()->back()->with('coupon_error', 'Seu carrinho está vazio.');
        }
        
        // ========== VALIDAÇÃO INTELIGENTE DE CUPONS ==========
        $userId = Auth::check() ? Auth::id() : null;
        
        // Validação inteligente
        $validation = Coupon::isValidForUser($coupon, $userId, $cartBooks);
        
        if (!$validation['valid']) {
            return redirect()->back()->with('coupon_error', $validation['message']);
        }
        
        // Aplicar cupom na sessão
        $couponData = [
            'id' => $coupon['id'],
            'code' => $coupon['code'],
            'discount' => $coupon['discount'],
            'type' => $coupon['type'],
        ];
        
        session(['cart_coupon' => $couponData]);
        
        return redirect()->back()->with('coupon_success', 'Cupom aplicado com sucesso!');
    }

    public function removeCoupon()
    {
        session()->forget('cart_coupon');
        return redirect()->back()->with('success', 'Cupom removido do carrinho.');
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
        $action = $request->input('action'); // 'increase' ou 'decrease'
        
        if (Auth::check()) {
            // Para usuários logados, atualizar no banco de dados
            $cartItem = CartItem::where('user_id', Auth::id())
                               ->where('book_id', $bookId)
                               ->first();
            
            if (!$cartItem) {
                return redirect()->back()->with('error', 'Livro não encontrado no carrinho');
            }
            
            if ($action === 'increase') {
                $cartItem->quantity++;
                $cartItem->save();
            } elseif ($action === 'decrease') {
                $cartItem->quantity--;
                
                if ($cartItem->quantity <= 0) {
                    $cartItem->delete();
                    return redirect()->back()->with('success', 'Livro removido do carrinho');
                }
                
                $cartItem->save();
            }
            
            if ($request->ajax()) {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
                return response()->json([
                    'success' => true,
                    'message' => 'Quantidade atualizada!',
                    'cart_count' => $cartCount
                ]);
            }
            
            return redirect()->back()->with('success', 'Quantidade atualizada!');
        } else {
            // Para usuários não logados, usar sessão
            $cart = session('cart', []);
            
            if (!isset($cart[$bookId])) {
                return redirect()->back()->with('error', 'Livro não encontrado no carrinho');
            }
            
            if ($action === 'increase') {
                $cart[$bookId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$bookId]['quantity']--;
                
                // Se a quantidade for 0, remove do carrinho
                if ($cart[$bookId]['quantity'] <= 0) {
                    unset($cart[$bookId]);
                    session(['cart' => $cart]);
                    return redirect()->back()->with('success', 'Livro removido do carrinho');
                }
            }
            
            session(['cart' => $cart]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quantidade atualizada!',
                    'cart_count' => count($cart)
                ]);
            }
            
            return redirect()->back()->with('success', 'Quantidade atualizada!');
        }
    }
}
