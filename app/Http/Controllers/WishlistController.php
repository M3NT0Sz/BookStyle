<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    private RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Exibir página da wishlist
     */
    public function index(): View
    {
        $wishlistItems = Auth::user()
            ->wishlist()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        // Obter recomendações baseadas na wishlist
        $recommendations = $this->recommendationService->getSmartRecommendations(
            Auth::id(),
            6
        );

        return view('wishlist.index', compact('wishlistItems', 'recommendations'));
    }

    /**
     * Adicionar livro à wishlist
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'price_alert' => 'nullable|numeric|min:0'
        ]);

        $user = Auth::user();

        // Verificar se já está na wishlist
        if ($user->hasInWishlist($request->book_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Este livro já está na sua lista de desejos!'
            ], 400);
        }

        $wishlistItem = $user->addToWishlist(
            $request->book_id,
            $request->price_alert
        );

        return response()->json([
            'success' => true,
            'message' => '❤️ Adicionado aos favoritos!',
            'wishlist_item' => $wishlistItem
        ]);
    }

    /**
     * Remover livro da wishlist
     */
    public function destroy($bookId): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasInWishlist($bookId)) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado na lista de desejos'
            ], 404);
        }

        $user->removeFromWishlist($bookId);

        return response()->json([
            'success' => true,
            'message' => 'Removido da lista de desejos'
        ]);
    }

    /**
     * Verificar se livro está na wishlist (AJAX)
     */
    public function check($bookId): JsonResponse
    {
        $inWishlist = Auth::user()->hasInWishlist($bookId);

        return response()->json([
            'inWishlist' => $inWishlist
        ]);
    }

    /**
     * Atualizar alerta de preço
     */
    public function updatePriceAlert(Request $request, $bookId): JsonResponse
    {
        $request->validate([
            'price_alert' => 'nullable|numeric|min:0'
        ]);

        $wishlistItem = Auth::user()
            ->wishlist()
            ->where('book_id', $bookId)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item não encontrado na wishlist'
            ], 404);
        }

        $wishlistItem->price_alert = $request->price_alert;
        $wishlistItem->notified = false; // Resetar notificação
        $wishlistItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Alerta de preço atualizado!'
        ]);
    }

    /**
     * Obter contagem de itens na wishlist
     */
    public function count(): JsonResponse
    {
        $count = Auth::user()->wishlist()->count();

        return response()->json([
            'count' => $count
        ]);
    }
}
