<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

echo "\n🧪 TESTE COMPLETO DO SISTEMA DE NOTIFICAÇÕES\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Criar ou buscar usuários de teste
    echo "📋 Preparando usuários de teste...\n";
    
    $buyer = User::firstOrCreate(
        ['email' => 'comprador@teste.com'],
        [
            'name' => 'Comprador Teste',
            'password' => bcrypt('password123')
        ]
    );
    
    $seller = User::firstOrCreate(
        ['email' => 'vendedor@teste.com'],
        [
            'name' => 'Vendedor Teste',
            'password' => bcrypt('password123')
        ]
    );
    
    echo "✅ Comprador: {$buyer->name} (ID: {$buyer->id})\n";
    echo "✅ Vendedor: {$seller->name} (ID: {$seller->id})\n\n";
    
    // Criar ou buscar livro do vendedor
    echo "📚 Preparando livro de teste...\n";
    
    $book = Book::firstOrCreate(
        [
            'name' => 'Livro de Teste para Notificações',
            'user_id' => $seller->id
        ],
        [
            'description' => 'Livro usado para testar o sistema de notificações',
            'author' => 'Autor Teste',
            'genre' => 'Ficção',
            'condition' => 'novo',
            'images' => json_encode(['default.jpg']),
            'price' => 50.00,
            'product_type' => 'fisico'
        ]
    );
    
    echo "✅ Livro: {$book->name} (ID: {$book->id})\n";
    echo "   Vendedor: {$seller->name}\n\n";
    
    // TESTE 1: Notificação de pedido criado
    echo "🧪 TESTE 1: Criar pedido\n";
    echo str_repeat("-", 60) . "\n";
    
    $order = Order::create([
        'user_id' => $buyer->id,
        'order_number' => 'TEST-' . time(),
        'total_amount' => 50.00,
        'discount_amount' => 0,
        'status' => 'pending',
        'payment_method' => 'credit_card'
    ]);
    
    OrderItem::create([
        'order_id' => $order->id,
        'book_id' => $book->id,
        'quantity' => 1,
        'price' => 50.00,
        'subtotal' => 50.00
    ]);
    
    NotificationService::notifyOrderCreated(
        $buyer->id,
        $order->id,
        $order->order_number,
        $order->total_amount
    );
    
    echo "✅ Pedido criado: {$order->order_number}\n";
    echo "   Total: R$ " . number_format($order->total_amount, 2, ',', '.') . "\n";
    
    $notification = $buyer->notifications()->where('type', 'order_created')->latest()->first();
    if ($notification) {
        echo "   📬 Notificação: {$notification->title}\n";
        echo "   💬 Mensagem: {$notification->message}\n";
    }
    echo "\n";
    
    // TESTE 2: Mudança de status do pedido
    echo "🧪 TESTE 2: Alterar status do pedido\n";
    echo str_repeat("-", 60) . "\n";
    
    $statuses = ['processing', 'shipped', 'delivered'];
    $oldStatus = $order->status;
    
    foreach ($statuses as $newStatus) {
        $order->status = $newStatus;
        $order->save();
        
        NotificationService::notifyOrderStatusChanged(
            $buyer->id,
            $order->id,
            $order->order_number,
            $oldStatus,
            $newStatus
        );
        
        echo "✅ Status alterado: {$oldStatus} → {$newStatus}\n";
        
        $notification = $buyer->notifications()->where('type', 'order_status')->latest()->first();
        if ($notification) {
            echo "   📬 Notificação: {$notification->title}\n";
            echo "   💬 Mensagem: {$notification->message}\n";
        }
        
        $oldStatus = $newStatus;
    }
    echo "\n";
    
    // TESTE 3: Solicitação de avaliação após entrega
    echo "🧪 TESTE 3: Solicitar avaliação após entrega\n";
    echo str_repeat("-", 60) . "\n";
    
    NotificationService::notifyOrderDelivered(
        $buyer->id,
        $order->id,
        $order->order_number
    );
    
    $notification = $buyer->notifications()->where('type', 'request_review')->latest()->first();
    if ($notification) {
        echo "✅ Solicitação enviada\n";
        echo "   📬 Notificação: {$notification->title}\n";
        echo "   💬 Mensagem: {$notification->message}\n";
    }
    echo "\n";
    
    // TESTE 4: Criar avaliação (notifica o vendedor)
    echo "🧪 TESTE 4: Criar avaliação do livro\n";
    echo str_repeat("-", 60) . "\n";
    
    $review = Review::create([
        'user_id' => $buyer->id,
        'book_id' => $book->id,
        'rating' => 5,
        'comment' => 'Livro excelente! Recomendo muito!',
        'order_id' => $order->id
    ]);
    
    echo "✅ Avaliação criada\n";
    echo "   ⭐ Rating: {$review->rating}/5\n";
    echo "   💬 Comentário: {$review->comment}\n";
    
    $notification = $seller->notifications()->where('type', 'review_received')->latest()->first();
    if ($notification) {
        echo "   📬 Notificação para vendedor: {$notification->title}\n";
        echo "   💬 Mensagem: {$notification->message}\n";
    }
    echo "\n";
    
    // TESTE 5: Cupom disponível
    echo "🧪 TESTE 5: Gerar cupom para usuário\n";
    echo str_repeat("-", 60) . "\n";
    
    $coupon = Coupon::create([
        'code' => 'AUTO-TEST-' . strtoupper(substr(md5(time()), 0, 6)),
        'type' => 'percent',
        'discount' => 15,
        'min_purchase_amount' => 30,
        'max_uses' => 1,
        'usage_count' => 0,
        'expires_at' => now()->addDays(30),
        'is_auto_generated' => true
    ]);
    
    NotificationService::notifyCouponAvailable(
        $buyer->id,
        $coupon->id,
        $coupon->code,
        $coupon->discount,
        $coupon->type
    );
    
    echo "✅ Cupom gerado: {$coupon->code}\n";
    echo "   💰 Desconto: {$coupon->discount}%\n";
    echo "   📅 Válido até: " . $coupon->expires_at->format('d/m/Y') . "\n";
    
    $notification = $buyer->notifications()->where('type', 'coupon_available')->latest()->first();
    if ($notification) {
        echo "   📬 Notificação: {$notification->title}\n";
        echo "   💬 Mensagem: {$notification->message}\n";
    }
    echo "\n";
    
    // RESUMO FINAL
    echo str_repeat("=", 60) . "\n";
    echo "📊 RESUMO DAS NOTIFICAÇÕES\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Notificações do comprador
    echo "🛒 Notificações do COMPRADOR ({$buyer->name}):\n";
    $buyerNotifications = $buyer->notifications()->orderBy('created_at', 'desc')->get();
    echo "   Total: {$buyerNotifications->count()}\n";
    echo "   Não lidas: " . $buyer->unreadNotificationsCount() . "\n\n";
    
    foreach ($buyerNotifications as $notif) {
        $icon = [
            'order_created' => '🎉',
            'order_status' => '📦',
            'coupon_available' => '🎁',
            'request_review' => '📝'
        ][$notif->type] ?? '📬';
        
        $readStatus = $notif->is_read ? '✓' : '●';
        echo "   {$icon} {$readStatus} [{$notif->type}] {$notif->title}\n";
        echo "      {$notif->message}\n";
        echo "      📅 " . $notif->created_at->format('d/m/Y H:i:s') . "\n\n";
    }
    
    // Notificações do vendedor
    echo "🏪 Notificações do VENDEDOR ({$seller->name}):\n";
    $sellerNotifications = $seller->notifications()->orderBy('created_at', 'desc')->get();
    echo "   Total: {$sellerNotifications->count()}\n";
    echo "   Não lidas: " . $seller->unreadNotificationsCount() . "\n\n";
    
    foreach ($sellerNotifications as $notif) {
        $icon = $notif->type === 'review_received' ? '⭐' : '📬';
        $readStatus = $notif->is_read ? '✓' : '●';
        echo "   {$icon} {$readStatus} [{$notif->type}] {$notif->title}\n";
        echo "      {$notif->message}\n";
        echo "      📅 " . $notif->created_at->format('d/m/Y H:i:s') . "\n\n";
    }
    
    echo "\n✅ TODOS OS TESTES CONCLUÍDOS COM SUCESSO!\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "📍 Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\n" . $e->getTraceAsString() . "\n";
}
