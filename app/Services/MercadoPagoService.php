<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }

    /**
     * Processar pagamento com cartão (checkout transparente)
     *
     * @param Order $order
     * @param string $cardToken
     * @return array
     */
    public function processCardPayment(Order $order, string $cardToken): array
    {
        try {
            $client = new PaymentClient();

            $paymentData = [
                'transaction_amount' => (float) $order->total_amount,
                'token' => $cardToken,
                'description' => 'Pedido #' . $order->order_number . ' - BookStyle',
                'installments' => 1,
                'payer' => [
                    'email' => $order->user->email ?? 'test@test.com',
                ],
                'external_reference' => (string) $order->id,
                'notification_url' => config('app.url') . '/api/mercadopago/webhook',
                'statement_descriptor' => 'BOOKSTYLE'
            ];

            $payment = $client->create($paymentData);

            Log::info('Pagamento processado com cartão', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'status' => $payment->status
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail
            ];

        } catch (MPApiException $e) {
            Log::error('Erro API do Mercado Pago no pagamento', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'api_response' => $e->getApiResponse()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento com cartão', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Criar pagamento PIX
     *
     * @param Order $order
     * @return array
     */
    public function createPixPayment(Order $order): array
    {
        try {
            $client = new PaymentClient();

            $paymentData = [
                'transaction_amount' => (float) $order->total_amount,
                'description' => 'Pedido #' . $order->order_number . ' - BookStyle',
                'payment_method_id' => 'pix',
                'payer' => [
                    'email' => $order->user->email ?? 'test@test.com',
                    'first_name' => $order->user->name ?? 'Cliente',
                ],
                'external_reference' => (string) $order->id,
                'notification_url' => config('app.url') . '/api/mercadopago/webhook',
            ];

            $payment = $client->create($paymentData);

            Log::info('Pagamento PIX criado', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'status' => $payment->status
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'qr_code' => $payment->point_of_interaction->transaction_data->qr_code ?? null,
                'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
                'ticket_url' => $payment->point_of_interaction->transaction_data->ticket_url ?? null,
            ];

        } catch (MPApiException $e) {
            Log::error('Erro API do Mercado Pago no PIX', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'api_response' => $e->getApiResponse()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar pagamento PIX', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Criar pagamento de boleto
     *
     * @param Order $order
     * @return array
     */
    public function createBoletoPayment(Order $order): array
    {
        try {
            $client = new PaymentClient();

            $paymentData = [
                'transaction_amount' => (float) $order->total_amount,
                'description' => 'Pedido #' . $order->order_number . ' - BookStyle',
                'payment_method_id' => 'bolbradesco',
                'payer' => [
                    'email' => $order->user->email ?? 'test@test.com',
                    'first_name' => $order->user->name ?? 'Cliente',
                ],
                'external_reference' => (string) $order->id,
                'notification_url' => config('app.url') . '/api/mercadopago/webhook',
            ];

            $payment = $client->create($paymentData);

            Log::info('Boleto criado', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'status' => $payment->status
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'ticket_url' => $payment->transaction_details->external_resource_url ?? null,
                'barcode' => $payment->barcode->content ?? null,
            ];

        } catch (MPApiException $e) {
            Log::error('Erro API do Mercado Pago no Boleto', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'api_response' => $e->getApiResponse()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar boleto', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Criar preferência de pagamento para um pedido
     *
     * @param Order $order
     * @return array
     */
    public function createPaymentPreference(Order $order): array
    {
        try {
            $client = new PreferenceClient();

            // Adicionar itens do pedido
            $items = [];
            foreach ($order->items as $orderItem) {
                $items[] = [
                    'id' => (string) $orderItem->book_id,
                    'title' => $orderItem->book_name,
                    'quantity' => (int) $orderItem->quantity,
                    'unit_price' => (float) $orderItem->price,
                    'currency_id' => 'BRL',
                    'description' => $orderItem->book && $orderItem->book->description 
                        ? substr($orderItem->book->description, 0, 250) 
                        : $orderItem->book_name
                ];
            }

            // Configurar preferência
            $preferenceData = [
                'items' => $items,
                'payer' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'back_urls' => [
                    'success' => config('mercadopago.success_url') . '?order_id=' . $order->id,
                    'failure' => config('mercadopago.failure_url') . '?order_id=' . $order->id,
                    'pending' => config('mercadopago.pending_url') . '?order_id=' . $order->id
                ],
                'auto_return' => 'approved',
                'external_reference' => (string) $order->id,
                'payment_methods' => [
                    'installments' => 12
                ],
                'notification_url' => config('app.url') . '/api/mercadopago/webhook',
                'statement_descriptor' => 'BOOKSTYLE'
            ];

            // Criar preferência
            $preference = $client->create($preferenceData);

            Log::info('Preferência do Mercado Pago criada', [
                'order_id' => $order->id,
                'preference_id' => $preference->id
            ]);

            return [
                'success' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                'sandbox_init_point' => $preference->sandbox_init_point
            ];

        } catch (MPApiException $e) {
            Log::error('Erro API do Mercado Pago', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'api_response' => $e->getApiResponse()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar preferência do Mercado Pago', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Buscar informações de um pagamento
     */
    public function getPaymentInfo($paymentId)
    {
        try {
            $client = new PaymentClient();
            return $client->get($paymentId);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar informações do pagamento', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Processar webhook do Mercado Pago
     *
     * @param array $data
     * @return bool
     */
    public function processWebhook(array $data): bool
    {
        try {
            Log::info('Webhook do Mercado Pago recebido', $data);

            if (!isset($data['type']) || $data['type'] !== 'payment') {
                return false;
            }

            $paymentId = $data['data']['id'] ?? null;
            
            if (!$paymentId) {
                return false;
            }

            // Buscar informações do pagamento
            $client = new PaymentClient();
            $payment = $client->get($paymentId);
            
            if (!$payment) {
                return false;
            }

            $orderId = $payment->external_reference;
            $order = Order::find($orderId);

            if (!$order) {
                Log::warning('Pedido não encontrado para o pagamento', [
                    'order_id' => $orderId,
                    'payment_id' => $paymentId
                ]);
                return false;
            }

            // Atualizar status do pedido baseado no status do pagamento
            $previousStatus = $order->payment_status;
            
            switch ($payment->status) {
                case 'approved':
                    $order->status = 'processing';
                    $order->payment_status = 'paid';
                    $order->payment_method = $payment->payment_type_id;
                    $order->payment_id = $paymentId;
                    $order->paid_at = now();
                    break;

                case 'pending':
                case 'in_process':
                    $order->payment_status = 'pending';
                    $order->payment_id = $paymentId;
                    break;

                case 'rejected':
                case 'cancelled':
                    $order->payment_status = 'failed';
                    $order->payment_id = $paymentId;
                    break;

                case 'refunded':
                case 'charged_back':
                    $order->status = 'cancelled';
                    $order->payment_status = 'refunded';
                    break;
            }

            $order->save();

            // Se o pagamento foi aprovado agora, gerar cupons inteligentes
            if ($payment->status === 'approved' && $previousStatus !== 'paid') {
                \App\Services\SmartCouponService::handleFirstPurchase($order->user_id);
            }

            Log::info('Status do pedido atualizado', [
                'order_id' => $order->id,
                'payment_status' => $order->payment_status,
                'order_status' => $order->status
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook do Mercado Pago', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return false;
        }
    }

    /**
     * Consultar status de um pagamento
     *
     * @param string $paymentId
     * @return array|null
     */
    public function getPaymentStatus(string $paymentId): ?array
    {
        try {
            $client = new PaymentClient();
            $payment = $client->get($paymentId);

            if (!$payment) {
                return null;
            }

            return [
                'id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'payment_type' => $payment->payment_type_id,
                'transaction_amount' => $payment->transaction_amount,
                'date_approved' => $payment->date_approved,
                'external_reference' => $payment->external_reference
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao consultar status do pagamento', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
}
