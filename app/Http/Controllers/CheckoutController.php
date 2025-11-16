<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Services\SmartCouponService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Configurar Mercado Pago
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }

    /**
     * Processar pagamento diretamente no checkout
     */
    public function processPayment(Request $request): JsonResponse
    {
        try {
            // Validação básica
            $rules = [
                'payment_method_id' => 'required|string',
                'token' => 'nullable|string', // Token do cartão (se for cartão)
                'installments' => 'nullable|integer|min:1|max:12',
                'payer' => 'required|array',
                'payer.email' => 'required|email',
                'payer.identification' => 'required|array',
                'payer.identification.type' => 'required|string',
                'payer.identification.number' => 'required|string',
                'billing_address' => 'required|array',
                'shipping_address' => 'required|array',
            ];

            $request->validate($rules);

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa estar logado para fazer um pedido.'
                ], 401);
            }

            $cartItems = Cart::getWithDetails();
            
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seu carrinho está vazio.'
                ], 400);
            }

            DB::beginTransaction();

            // Calcular totais
            $subtotal = 0;
            foreach ($cartItems as $cartItem) {
                $subtotal += $cartItem->subtotal;
            }

            // Aplicar desconto de cupom se houver
            $discount = 0;
            $couponCode = null;
            $couponId = null;
            if (session('cart_coupon')) {
                $cartCoupon = session('cart_coupon');
                $discount = $cartCoupon['type'] == 'percent' 
                    ? ($subtotal * ($cartCoupon['discount'] / 100)) 
                    : $cartCoupon['discount'];
                $discount = min($discount, $subtotal);
                $couponCode = $cartCoupon['code'];
                $couponId = $cartCoupon['id'] ?? null;
            }

            $finalTotal = max($subtotal - $discount, 0);

            // Criar o pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $finalTotal,
                'discount_amount' => $discount,
                'coupon_code' => $couponCode,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method_id,
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address,
            ]);

            // Criar os itens do pedido
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cartItem->book->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->book->price
                ]);
            }

            // Marcar cupom como usado
            if ($couponId) {
                Coupon::markAsUsed($couponId);
                if (Auth::check()) {
                    Auth::user()->markCouponAsUsed($couponId, $order->id);
                }
            }

            // Processar pagamento com Mercado Pago
            $paymentClient = new PaymentClient();

            $paymentData = [
                'transaction_amount' => (float) $finalTotal,
                'token' => $request->token,
                'description' => 'Pedido #' . $order->order_number . ' - BookStyle',
                'installments' => (int) ($request->installments ?? 1),
                'payment_method_id' => $request->payment_method_id,
                'payer' => [
                    'email' => $request->payer['email'],
                    'identification' => [
                        'type' => $request->payer['identification']['type'],
                        'number' => $request->payer['identification']['number']
                    ]
                ],
                'external_reference' => (string) $order->id,
                'notification_url' => route('mercadopago.webhook'),
            ];

            // Adicionar informações adicionais se houver
            if ($request->has('issuer_id')) {
                $paymentData['issuer_id'] = $request->issuer_id;
            }

            $payment = $paymentClient->create($paymentData);

            // Atualizar pedido com informações do pagamento
            $order->update([
                'payment_id' => $payment->id,
                'payment_status' => $payment->status,
            ]);

            // Se pagamento aprovado, atualizar status do pedido
            if ($payment->status === 'approved') {
                $order->update([
                    'status' => 'processing',
                    'paid_at' => now()
                ]);
            }

            // Limpar carrinho
            \App\Models\CartItem::where('user_id', Auth::id())->delete();
            session()->forget('cart');
            session()->forget('cart_coupon');
            session()->save();

            DB::commit();

            // Notificar pedido criado
            NotificationService::notifyOrderCreated(
                Auth::id(),
                $order->id,
                $order->order_number,
                $finalTotal
            );

            // Triggers de cupons
            SmartCouponService::handleFirstPurchase(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Pagamento processado com sucesso!',
                'payment_status' => $payment->status,
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'redirect_url' => $this->getRedirectUrl($payment->status, $order->id)
            ]);

        } catch (MPApiException $e) {
            DB::rollBack();
            
            \Log::error('Erro na API do Mercado Pago: ' . $e->getMessage());
            \Log::error('API Response: ' . json_encode($e->getApiResponse()));
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage(),
                'error_details' => $e->getApiResponse()
            ], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erro ao processar checkout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter URL de redirecionamento baseado no status do pagamento
     */
    private function getRedirectUrl(string $status, int $orderId): string
    {
        switch ($status) {
            case 'approved':
                return route('payment.success', ['order' => $orderId]);
            case 'pending':
            case 'in_process':
                return route('payment.pending', ['order' => $orderId]);
            case 'rejected':
            default:
                return route('payment.failure', ['order' => $orderId]);
        }
    }
}
