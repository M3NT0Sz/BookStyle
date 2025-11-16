<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private MercadoPagoService $mercadoPagoService;

    public function __construct(MercadoPagoService $mercadoPagoService)
    {
        $this->mercadoPagoService = $mercadoPagoService;
    }

    /**
     * Criar checkout de pagamento para um pedido
     */
    public function checkout(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with(['items.book', 'user'])
            ->firstOrFail();

        // Verificar se o pedido já foi pago
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('orders.show', $order->id)
                ->with('error', 'Este pedido já foi pago.');
        }

        // Criar preferência de pagamento no Mercado Pago
        $result = $this->mercadoPagoService->createPaymentPreference($order);

        if (!$result['success']) {
            return back()->with('error', 'Erro ao criar checkout de pagamento: ' . $result['error']);
        }

        // Salvar preference_id no pedido
        $order->preference_id = $result['preference_id'];
        $order->save();

        // Redirecionar para o checkout do Mercado Pago
        $checkoutUrl = config('app.env') === 'production' 
            ? $result['init_point'] 
            : $result['sandbox_init_point'];

        return redirect($checkoutUrl);
    }

    /**
     * Página de pagamento PIX com QR Code
     */
    public function pix(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Pedido não encontrado.');
        }

        // Buscar dados do PIX da sessão ou do banco
        $pixData = session('pix_data');
        
        if (!$pixData && $order->payment_id) {
            // Tentar buscar do Mercado Pago
            $mercadoPagoService = new MercadoPagoService();
            $paymentInfo = $mercadoPagoService->getPaymentInfo($order->payment_id);
            
            if ($paymentInfo) {
                $pixData = [
                    'qr_code' => $paymentInfo->point_of_interaction->transaction_data->qr_code ?? null,
                    'qr_code_base64' => $paymentInfo->point_of_interaction->transaction_data->qr_code_base64 ?? null,
                ];
            }
        }

        return view('payment.pix', compact('order', 'pixData'));
    }

    /**
     * Página de pagamento Boleto
     */
    public function boleto(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Pedido não encontrado.');
        }

        // Buscar dados do Boleto da sessão
        $boletoData = session('boleto_data');

        return view('payment.boleto', compact('order', 'boletoData'));
    }

    /**
     * Página de sucesso após pagamento
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order') ?? $request->query('order_id');
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Pedido não encontrado.');
        }

        // Atualizar informações do pagamento
        if ($paymentId) {
            $order->payment_id = $paymentId;
            
            if ($status === 'approved') {
                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->paid_at = now();
            }
            
            $order->save();
        }

        return view('payment.success', compact('order'));
    }

    /**
     * Página de falha no pagamento
     */
    public function failure(Request $request)
    {
        $orderId = $request->query('order') ?? $request->query('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Pedido não encontrado.');
        }

        $order->payment_status = 'failed';
        $order->save();

        return view('payment.failure', compact('order'));
    }

    /**
     * Página de pagamento pendente
     */
    public function pending(Request $request)
    {
        $orderId = $request->query('order') ?? $request->query('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Pedido não encontrado.');
        }

        $order->payment_status = 'pending';
        $order->save();

        return view('payment.pending', compact('order'));
    }

    /**
     * Webhook para receber notificações do Mercado Pago
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook recebido do Mercado Pago', $request->all());

        $this->mercadoPagoService->processWebhook($request->all());

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Consultar status de um pagamento
     */
    public function checkStatus($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->payment_id) {
            return response()->json([
                'success' => false,
                'message' => 'Pagamento ainda não processado.'
            ]);
        }

        $paymentStatus = $this->mercadoPagoService->getPaymentStatus($order->payment_id);

        if (!$paymentStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível consultar o status do pagamento.'
            ]);
        }

        return response()->json([
            'success' => true,
            'payment' => $paymentStatus,
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]
        ]);
    }
}
