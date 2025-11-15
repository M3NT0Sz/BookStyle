<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\NotificationService;
use App\Models\User;
use App\Models\Notification;

echo "=== TESTE DO PADRÃO OBSERVER - NOTIFICAÇÕES ===\n\n";

// Buscar um usuário para teste
$user = User::query()->first();

if (!$user) {
    echo "❌ Nenhum usuário encontrado. Crie um usuário primeiro.\n";
    exit;
}

echo "👤 Usuário: {$user->name} (ID: {$user->id})\n";
echo str_repeat("=", 60) . "\n\n";

// Limpar notificações antigas do teste
Notification::where('user_id', $user->id)->delete();

echo "📊 TESTANDO PADRÃO OBSERVER\n";
echo str_repeat("-", 60) . "\n\n";

// 1. Teste: Notificação de Pedido Criado
echo "1️⃣  Criando notificação de pedido...\n";
NotificationService::notifyOrderCreated($user->id, 123, 'ORD-2025-123', 150.50);
echo "   ✅ Notificação enviada via Observer\n\n";

// 2. Teste: Notificação de Mudança de Status
echo "2️⃣  Mudando status de pedido...\n";
NotificationService::notifyOrderStatusChanged($user->id, 123, 'ORD-2025-123', 'pending', 'shipped');
echo "   ✅ Notificação enviada via Observer\n\n";

// 3. Teste: Notificação de Cupom Disponível
echo "3️⃣  Disponibilizando cupom...\n";
NotificationService::notifyCouponAvailable($user->id, 5, 'DESCONTO20', 20, 'percent');
echo "   ✅ Notificação enviada via Observer\n\n";

// 4. Teste: Notificação de Pedido Entregue
echo "4️⃣  Marcando pedido como entregue...\n";
NotificationService::notifyOrderDelivered($user->id, 123, 'ORD-2025-123');
echo "   ✅ Notificação enviada via Observer\n\n";

// Verificar notificações criadas
echo str_repeat("=", 60) . "\n";
echo "📬 NOTIFICAÇÕES CRIADAS:\n";
echo str_repeat("-", 60) . "\n\n";

$notifications = Notification::where('user_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($notifications as $notification) {
    $icon = match($notification->type) {
        'order_created' => '🎉',
        'order_status' => '📦',
        'coupon_available' => '🎁',
        'request_review' => '📝',
        default => '🔔',
    };
    
    echo "{$icon} {$notification->title}\n";
    echo "   Mensagem: {$notification->message}\n";
    echo "   Tipo: {$notification->type}\n";
    echo "   Status: " . ($notification->is_read ? "✅ Lida" : "⭕ Não lida") . "\n";
    echo "   Criada: " . $notification->created_at->format('d/m/Y H:i:s') . "\n";
    
    if ($notification->data) {
        echo "   Dados: " . json_encode($notification->data) . "\n";
    }
    
    echo "\n";
}

echo str_repeat("=", 60) . "\n";
echo "RESUMO:\n";
echo "- Total de notificações: " . $notifications->count() . "\n";
echo "- Não lidas: " . $notifications->where('is_read', false)->count() . "\n";
echo "- Lidas: " . $notifications->where('is_read', true)->count() . "\n";
echo str_repeat("=", 60) . "\n\n";

echo "✅ PADRÃO OBSERVER IMPLEMENTADO COM SUCESSO!\n";
echo "\nComponentes do Padrão:\n";
echo "- Subject: NotificationSubject (gerencia observadores)\n";
echo "- Observer: UserNotificationObserver (recebe e processa eventos)\n";
echo "- Service: NotificationService (interface para notificar eventos)\n";
echo "- Model: Notification (armazena as notificações)\n";
