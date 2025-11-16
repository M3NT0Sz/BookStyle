<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Services\SmartCouponService;

// ID do pedido para aprovar
$orderId = 12;

$order = Order::find($orderId);

if (!$order) {
    echo "Pedido #{$orderId} não encontrado!\n";
    exit(1);
}

echo "Pedido #{$orderId}\n";
echo "Status atual: {$order->payment_status}\n\n";

$order->payment_status = 'paid';
$order->status = 'processing';
$order->paid_at = now();
$order->save();

echo "✅ Pagamento aprovado!\n";
echo "Status atualizado: {$order->payment_status}\n\n";

// Gerar cupom de primeira compra
SmartCouponService::handleFirstPurchase($order->user_id);
echo "🎁 Cupom de primeira compra gerado!\n";
