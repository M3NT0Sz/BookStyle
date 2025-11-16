<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$order = Order::find(15);

if ($order) {
    $order->payment_status = 'paid';
    $order->paid_at = now();
    $order->save();
    
    echo "✅ Pedido #{$order->id} atualizado!\n";
    echo "Status: {$order->status}\n";
    echo "Payment Status: {$order->payment_status}\n";
    echo "Paid At: {$order->paid_at}\n";
} else {
    echo "❌ Pedido não encontrado\n";
}
