<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Mostrar histórico de pedidos do usuário
     */
    public function index(): View
    {
        $orders = Order::with(['orderItems.book'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Mostrar detalhes de um pedido específico
     */
    public function show(Order $order): View
    {
        // Verificar se o pedido pertence ao usuário logado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $order->load(['orderItems.book']);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Processar pedido - converter carrinho em pedido
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação básica
        $rules = [
            'payment_method' => 'required|string|in:credit_card,debit_card,pix,boleto',
            'billing_address' => 'required|array',
            'billing_address.street' => 'required|string',
            'billing_address.city' => 'required|string',
            'billing_address.state' => 'required|string',
            'billing_address.postal_code' => 'required|string',
            'billing_address.number' => 'required|string',
            'billing_address.neighborhood' => 'required|string',
            'billing_address.complement' => 'nullable|string', // Complemento opcional
            'notes' => 'nullable|string'
        ];

        // Se não é o mesmo endereço, validar campos de entrega
        if (!$request->has('same_address')) {
            $rules['shipping_address'] = 'required|array';
            $rules['shipping_address.street'] = 'required|string';
            $rules['shipping_address.city'] = 'required|string';
            $rules['shipping_address.state'] = 'required|string';
            $rules['shipping_address.postal_code'] = 'required|string';
            $rules['shipping_address.number'] = 'required|string';
            $rules['shipping_address.neighborhood'] = 'required|string';
            $rules['shipping_address.complement'] = 'nullable|string'; // Complemento opcional
        }

        // Se método de pagamento é cartão, validar campos do cartão
        if (in_array($request->payment_method, ['credit_card', 'debit_card'])) {
            $rules['card_name'] = 'required|string|min:3';
            $rules['card_number'] = 'required|string|min:13';
            $rules['card_expiry'] = 'required|string|size:5';
            $rules['card_cvv'] = 'required|string|min:3|max:4';
        }

        $request->validate($rules, [
            'billing_address.street.required' => 'O campo rua é obrigatório no endereço de cobrança.',
            'billing_address.city.required' => 'O campo cidade é obrigatório no endereço de cobrança.',
            'billing_address.state.required' => 'O campo estado é obrigatório no endereço de cobrança.',
            'billing_address.postal_code.required' => 'O campo CEP é obrigatório no endereço de cobrança.',
            'billing_address.number.required' => 'O campo número é obrigatório no endereço de cobrança.',
            'billing_address.neighborhood.required' => 'O campo bairro é obrigatório no endereço de cobrança.',
            'shipping_address.street.required' => 'O campo rua é obrigatório no endereço de entrega.',
            'shipping_address.city.required' => 'O campo cidade é obrigatório no endereço de entrega.',
            'shipping_address.state.required' => 'O campo estado é obrigatório no endereço de entrega.',
            'shipping_address.postal_code.required' => 'O campo CEP é obrigatório no endereço de entrega.',
            'shipping_address.number.required' => 'O campo número é obrigatório no endereço de entrega.',
            'shipping_address.neighborhood.required' => 'O campo bairro é obrigatório no endereço de entrega.',
            'card_name.required' => 'O nome no cartão é obrigatório.',
            'card_number.required' => 'O número do cartão é obrigatório.',
            'card_expiry.required' => 'A validade do cartão é obrigatória.',
            'card_cvv.required' => 'O CVV do cartão é obrigatório.',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para fazer um pedido.');
        }

        $cartItems = Cart::getWithDetails();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        try {
            DB::beginTransaction();

            // Se usar o mesmo endereço, copiar billing para shipping
            $shippingAddress = $request->shipping_address;
            if ($request->has('same_address')) {
                $shippingAddress = $request->billing_address;
            }

            // Criar o pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => 0, // Será calculado depois
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'billing_address' => $request->billing_address,
                'shipping_address' => $shippingAddress,
                'notes' => $request->notes
            ]);

            $total = 0;

            // Criar os itens do pedido
            foreach ($cartItems as $cartItem) {
                $book = $cartItem->book;
                
                // Verificar se o livro ainda existe e tem estoque (se aplicável)
                if (!$book) {
                    throw new \Exception('Um dos livros no carrinho não foi encontrado.');
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $book->price
                ]);

                $total += $orderItem->subtotal;
            }

            // Atualizar o total do pedido
            $order->update(['total' => $total]);

            // Limpar o carrinho do usuário específico - LIMPEZA ROBUSTA
            if (Auth::check()) {
                $deleted = \App\Models\CartItem::where('user_id', Auth::id())->delete();
                \Log::info('CartItems deletados: ' . $deleted . ' para usuário: ' . Auth::id());
            }
            
            // Limpar sessão completamente
            session()->forget('cart');
            session()->save(); // Forçar salvamento da sessão

            DB::commit();

            // Redirecionar para o carrinho para mostrar que foi esvaziado
            return redirect()->route('cart.index')
                ->with('success', 'Pedido #' . $order->order_number . ' realizado com sucesso! Seu carrinho foi esvaziado.')
                ->with('order_created', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Em caso de erro, não limpar o carrinho
            return redirect()->route('cart.index')
                ->with('error', 'Erro ao processar o pedido: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar página de checkout
     */
    public function checkout(): View
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Cart::getWithDetails();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $total = Cart::total();
        
        return view('orders.checkout', compact('cartItems', 'total'));
    }

    /**
     * Cancelar pedido
     */
    public function cancel(Order $order): RedirectResponse
    {
        // Verificar se o pedido pertence ao usuário logado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Este pedido não pode ser cancelado.');
        }

        $order->cancel();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pedido cancelado com sucesso.');
    }

    /**
     * Filtrar pedidos por status
     */
    public function filterByStatus(Request $request): View
    {
        $status = $request->get('status');
        
        $query = Order::with(['orderItems.book'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->byStatus($status);
        }

        $orders = $query->paginate(10);
        
        return view('orders.index', compact('orders', 'status'));
    }

    /**
     * Buscar pedidos
     */
    public function search(Request $request): View
    {
        $search = $request->get('search');
        
        $orders = Order::with(['orderItems.book'])
            ->forUser(Auth::id())
            ->where('order_number', 'LIKE', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('orders.index', compact('orders', 'search'));
    }
}