<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        $userAuth = Auth::user();
        $user = User::find($userAuth->id);
        
        // Garante que o usuário tenha a propriedade image
        if (!isset($user['image']) || empty($user['image'])) {
            $user['image'] = 'perfil.png';
        }
        
        // Busca livros do usuário usando Eloquent
        $books = collect([]); // Inicializa como coleção vazia
        try {
            $allBooks = Book::getAllBooks();
            if ($allBooks) {
                $userBooks = array_filter($allBooks, function($book) use ($user) {
                    return isset($book['user_id']) && $book['user_id'] == $user['id'];
                });
                $books = collect(array_map(function($book) { 
                    return (object) $book; 
                }, $userBooks));
            }
        } catch (\Exception $e) {
            // Se houver erro, mantém coleção vazia
            $books = collect([]);
        }
        
        // Buscar pedidos do usuário
        $orders = \App\Models\Order::where('user_id', $userAuth->id)
            ->with(['orderItems.book'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Garante que $user seja objeto para a view
        return view('user.profile', [
            'books' => $books,
            'user' => (object) $user,
            'orders' => $orders
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Para requisições AJAX de upload de imagem, validar apenas a imagem
            if (($request->ajax() || $request->wantsJson()) && $request->hasFile('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);
                
                // Buscar usuário e imagem antiga ANTES de salvar nova
                $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
                $stmt = $pdo->prepare('SELECT image FROM users WHERE id = ?');
                $stmt->execute([$id]);
                $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
                $oldImage = $userData['image'] ?? null;
                
                // Salvar nova imagem
                $imagePath = $request->file('image')->store('profile', 'public');
                
                // Atualizar no banco
                User::updateProfile($id, ['image' => $imagePath]);
                
                // Deletar imagem antiga se não for a padrão (APÓS salvar nova)
                if ($oldImage && $oldImage !== 'perfil.png' && $oldImage !== 'profile/perfil.png') {
                    $oldImagePath = preg_replace('#^storage/#', '', trim($oldImage));
                    
                    if (\Storage::disk('public')->exists($oldImagePath)) {
                        \Storage::disk('public')->delete($oldImagePath);
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Foto atualizada com sucesso!',
                    'image_url' => asset('storage/' . $imagePath)
                ]);
            }
            
            // Validação completa para update normal do perfil
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Buscar imagem antiga ANTES de salvar nova
                $pdo = \App\Models\DatabaseSingleton::getInstance()->getConnection();
                $stmt = $pdo->prepare('SELECT image FROM users WHERE id = ?');
                $stmt->execute([$id]);
                $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
                $oldImage = $userData['image'] ?? null;
                
                $imagePath = $request->file('image')->store('profile', 'public');
                $data['image'] = $imagePath;
                
                // Atualizar perfil
                User::updateProfile($id, $data);
                
                // Deletar imagem antiga se não for a padrão (APÓS salvar nova)
                if ($oldImage && $oldImage !== 'perfil.png' && $oldImage !== 'profile/perfil.png') {
                    $oldImagePath = preg_replace('#^storage/#', '', trim($oldImage));
                    if (\Storage::disk('public')->exists($oldImagePath)) {
                        \Storage::disk('public')->delete($oldImagePath);
                    }
                }
            } else {
                User::updateProfile($id, $data);
            }

            return redirect()->route('user.profile')->with('success', 'Perfil atualizado com sucesso!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
            
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar perfil: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erro ao atualizar perfil.');
        }
    }
}
