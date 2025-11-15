<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Listar notificações do usuário
     */
    public function index(): View
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Auth::user()->unreadNotificationsCount();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Obter notificações não lidas (AJAX)
     */
    public function unread(): JsonResponse
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count(),
            'total' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead($id): JsonResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como lida',
        ]);
    }

    /**
     * Marcar todas como lidas
     */
    public function markAllAsRead(): JsonResponse
    {
        Auth::user()
            ->notifications()
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Todas as notificações foram marcadas como lidas',
        ]);
    }

    /**
     * Deletar notificação
     */
    public function destroy($id): JsonResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificação excluída',
        ]);
    }

    /**
     * Limpar todas as notificações lidas
     */
    public function clearRead(): JsonResponse
    {
        Auth::user()
            ->notifications()
            ->read()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificações lidas foram excluídas',
        ]);
    }
}
