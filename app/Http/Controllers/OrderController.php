<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\Coupon;
use App\Services\SmartCouponService;
use App\Services\NotificationService;
use App\Services\MercadoPagoService;
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
                'total_amount' => 0, // Será calculado depois
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

            // Aplicar desconto de cupom se houver
            $discount = 0;
            $couponCode = null;
            $couponId = null;
            if (session('cart_coupon')) {
                $cartCoupon = session('cart_coupon');
                $discount = $cartCoupon['type'] == 'percent' 
                    ? ($total * ($cartCoupon['discount'] / 100)) 
                    : $cartCoupon['discount'];
                $discount = min($discount, $total); // Não pode ser maior que o total
                $couponCode = $cartCoupon['code'];
                $couponId = $cartCoupon['id'] ?? null;
                
                // Marcar cupom como usado no sistema antigo
                if ($couponId) {
                    Coupon::markAsUsed($couponId);
                }
            }

            $finalTotal = max($total - $discount, 0);

            // Atualizar o total do pedido
            $order->update([
                'total_amount' => $finalTotal,
                'discount_amount' => $discount,
                'coupon_code' => $couponCode
            ]);

            // NOVO: Registrar uso do cupom pelo usuário (sistema de uso único + cooldown)
            if ($couponId && Auth::check()) {
                $user = Auth::user();
                $user->markCouponAsUsed($couponId, $order->id);
            }

            // Limpar o carrinho do usuário específico - LIMPEZA ROBUSTA
            if (Auth::check()) {
                $deleted = \App\Models\CartItem::where('user_id', Auth::id())->delete();
                \Log::info('CartItems deletados: ' . $deleted . ' para usuário: ' . Auth::id());
            }
            
            // Limpar sessão completamente (incluindo cupom)
            session()->forget('cart');
            session()->forget('cart_coupon');
            session()->save(); // Forçar salvamento da sessão

            DB::commit();

            // ========== NOTIFICAÇÃO: PEDIDO CRIADO ==========
            NotificationService::notifyOrderCreated(
                Auth::id(),
                $order->id,
                $order->order_number,
                $finalTotal
            );

            // ========== PROCESSAR PAGAMENTO COM MERCADO PAGO (CHECKOUT TRANSPARENTE) ==========
            // Se tiver token do cartão, processar pagamento direto
            if ($request->has('card_token') && $request->card_token) {
                try {
                    $mercadoPagoService = new MercadoPagoService();
                    $paymentResult = $mercadoPagoService->processCardPayment($order, $request->card_token);
                    
                    if ($paymentResult['success']) {
                        // Atualizar pedido com dados do pagamento
                        $order->update([
                            'payment_id' => $paymentResult['payment_id'],
                            'payment_status' => $paymentResult['status']
                        ]);
                        
                        // Se aprovado, marcar como pago
                        if ($paymentResult['status'] === 'approved') {
                            $order->update([
                                'status' => 'processing',
                                'paid_at' => now()
                            ]);
                            
                            // ========== TRIGGERS INTELIGENTES DE CUPONS (APENAS APÓS PAGAMENTO APROVADO) ==========
                            SmartCouponService::handleFirstPurchase(Auth::id());
                            
                            return redirect()->route('payment.success', ['order' => $order->id])
                                ->with('success', 'Pagamento aprovado! Seu pedido está sendo processado.');
                        } elseif ($paymentResult['status'] === 'pending' || $paymentResult['status'] === 'in_process') {
                            return redirect()->route('payment.pending', ['order' => $order->id])
                                ->with('info', 'Pagamento pendente. Aguardando confirmação.');
                        } else {
                            return redirect()->route('payment.failure', ['order' => $order->id])
                                ->with('error', 'Pagamento recusado. Tente outro cartão.');
                        }
                    } else {
                        // Erro no pagamento
                        return redirect()->route('orders.show', $order)
                            ->with('error', 'Erro ao processar pagamento: ' . ($paymentResult['error'] ?? 'Erro desconhecido'))
                            ->with('warning', 'Seu pedido foi criado. Você pode tentar pagar novamente clicando em "Pagar Agora".');
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao processar pagamento com cartão: ' . $e->getMessage());
                    
                    return redirect()->route('orders.show', $order)
                        ->with('error', 'Erro ao processar pagamento: ' . $e->getMessage())
                        ->with('warning', 'Seu pedido foi criado. Você pode tentar pagar novamente.');
                }
            }
            
            // Se não tiver token, processar PIX ou Boleto
            if ($request->payment_method === 'pix') {
                try {
                    $mercadoPagoService = new MercadoPagoService();
                    $pixResult = $mercadoPagoService->createPixPayment($order);
                    
                    if ($pixResult['success']) {
                        // Atualizar pedido com dados do PIX
                        $order->update([
                            'payment_id' => $pixResult['payment_id'],
                            'payment_status' => $pixResult['status']
                        ]);
                        
                        // Redirecionar para página de PIX com QR Code
                        return redirect()->route('payment.pix', ['order' => $order->id])
                            ->with('pix_data', $pixResult);
                    } else {
                        return redirect()->route('orders.show', $order)
                            ->with('error', 'Erro ao gerar PIX: ' . ($pixResult['error'] ?? 'Erro desconhecido'));
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro ao criar pagamento PIX: ' . $e->getMessage());
                    
                    return redirect()->route('orders.show', $order)
                        ->with('error', 'Erro ao processar pagamento PIX: ' . $e->getMessage());
                }
            }
            
            if ($request->payment_method === 'boleto') {
                // Criar pedido com pagamento pendente (boleto será gerado na tela de boleto)
                // Em ambiente de teste, o Mercado Pago pode não suportar boleto
                // Então vamos apenas redirecionar para a página informativa
                
                return redirect()->route('payment.boleto', ['order' => $order->id])
                    ->with('info', 'Boleto será gerado. Em ambiente de teste, use PIX ou cartão para testar pagamentos.');
            }
            
            // Fallback: método de pagamento não suportado
            return redirect()->route('orders.show', $order)
                ->with('error', 'Método de pagamento não suportado: ' . $request->payment_method);

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
    public function checkout()
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