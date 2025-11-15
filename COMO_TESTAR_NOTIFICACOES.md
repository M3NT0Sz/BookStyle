# COMANDOS TINKER PARA TESTAR NOTIFICAÇÕES

# 1. Abrir Tinker
php artisan tinker

# 2. Importar classes necessárias
use App\Services\NotificationService;
use App\Models\User;

# 3. Pegar seu usuário (substitua o ID)
$user = User::find(1);

# 4. TESTAR NOTIFICAÇÃO DE PEDIDO
NotificationService::notifyOrderCreated($user->id, 123, 'PEDIDO-TESTE-001', 150.00);

# 5. TESTAR NOTIFICAÇÃO DE STATUS
NotificationService::notifyOrderStatusChanged($user->id, 123, 'PEDIDO-TESTE-001', 'pending', 'shipped');

# 6. TESTAR NOTIFICAÇÃO DE CUPOM
NotificationService::notifyCouponAvailable($user->id, 1, 'DESCONTO20', 20, 'percent');

# 7. TESTAR NOTIFICAÇÃO DE AVALIAÇÃO (para vendedor)
NotificationService::notifyReviewReceived($user->id, 1, 10, 'Nome do Livro', 5);

# 8. TESTAR SOLICITAÇÃO DE AVALIAÇÃO
NotificationService::notifyOrderDelivered($user->id, 123, 'PEDIDO-TESTE-001');

# 9. VER NOTIFICAÇÕES DO USUÁRIO
$user->notifications;

# 10. VER APENAS NÃO LIDAS
$user->unreadNotifications()->get();

# 11. CONTAR NÃO LIDAS
$user->unreadNotificationsCount();

# 12. MARCAR TODAS COMO LIDAS
$user->notifications()->update(['is_read' => true, 'read_at' => now()]);
