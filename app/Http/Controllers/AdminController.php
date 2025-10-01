<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Adapters\CsvExportAdapter;
use App\Adapters\JsonExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        return view('admin.dashboard');
    }

    public function books()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $books = Book::getAllBooks();
        return view('admin.books', compact('books'));
    }

    public function exportBooks($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $books = Book::getAllBooks();

        if ($format === 'csv') {
            $adapter = new CsvExportAdapter();
            $content = $adapter->export($books);
            $filename = 'books_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } elseif ($format === 'json') {
            $adapter = new JsonExport();
            $content = $adapter->export($books);
            $filename = 'books_' . date('Y-m-d_H-i-s') . '.json';
            
            return response($content, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return redirect()->route('admin.books')->with('error', 'Formato de exportação inválido.');
    }

    public function users()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function exportUsers($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $users = User::all()->toArray();

        if ($format === 'csv') {
            $adapter = new CsvExportAdapter();
            $content = $adapter->export($users);
            $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } elseif ($format === 'json') {
            $adapter = new JsonExport();
            $content = $adapter->export($users);
            $filename = 'users_' . date('Y-m-d_H-i-s') . '.json';
            
            return response($content, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return redirect()->route('admin.users')->with('error', 'Formato de exportação inválido.');
    }

    public function showBook($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $book = Book::findBook($id);
        if (!$book) {
            return redirect()->route('admin.books')->with('error', 'Livro não encontrado.');
        }
        $userId = is_array($book) ? ($book['user_id'] ?? null) : $book->user_id;
        $user = $userId ? \App\Models\User::find($userId) : null;
        return view('books.show', compact('book', 'user'));
    }

    public function destroyBook($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        
        // Usar o modelo Eloquent em vez do método estático
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            return redirect()->route('admin.books')->with('success', 'Livro deletado com sucesso!');
        }
        
        return redirect()->route('admin.books')->with('error', 'Livro não encontrado.');
    }

    public function editUser($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Usuário não encontrado.');
        }
        return view('admin.user_edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'Usuário não encontrado.');
        }
        $data = $request->validate([
            'is_admin' => 'required|boolean',
        ]);
        $user->is_admin = $data['is_admin'];
        $user->save();
        return redirect()->route('admin.users')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function coupons()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }
        
        $coupons = Coupon::getAllCoupons();
        
        // Calcular estatísticas dos cupons
        $stats = [
            'total_coupons' => count($coupons),
            'active_coupons' => count(array_filter($coupons, function($coupon) {
                return $coupon['is_active'] ?? false;
            })),
            'auto_generated' => count(array_filter($coupons, function($coupon) {
                return $coupon['is_auto_generated'] ?? false;
            })),
            'total_usage' => array_sum(array_map(function($coupon) {
                return $coupon['used_count'] ?? 0;
            }, $coupons))
        ];
        
        return view('admin.coupons', compact('coupons', 'stats'));
    }

    public function exportCoupons($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $coupons = Coupon::getAllCoupons();

        if ($format === 'csv') {
            $adapter = new CsvExportAdapter();
            $content = $adapter->export($coupons);
            $filename = 'coupons_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } elseif ($format === 'json') {
            $adapter = new JsonExport();
            $content = $adapter->export($coupons);
            $filename = 'coupons_' . date('Y-m-d_H-i-s') . '.json';
            
            return response($content, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return redirect()->route('admin.coupons')->with('error', 'Formato de exportação inválido.');
    }

    public function orders()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $orders = Order::with(['user', 'orderItems.book'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function exportOrders($format)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $orders = Order::with(['user', 'orderItems.book'])->get()->toArray();

        if ($format === 'csv') {
            $adapter = new CsvExportAdapter();
            $content = $adapter->export($orders);
            $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
            
            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } elseif ($format === 'json') {
            $adapter = new JsonExport();
            $content = $adapter->export($orders);
            $filename = 'orders_' . date('Y-m-d_H-i-s') . '.json';
            
            return response($content, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return redirect()->route('admin.orders')->with('error', 'Formato de exportação inválido.');
    }

    public function showOrder($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $order = Order::with(['user', 'orderItems.book'])->find($id);
        
        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Pedido não encontrado.');
        }

        return view('admin.order_show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::find($id);
        
        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Pedido não encontrado.');
        }

        $order->status = $request->status;
        
        // Atualizar timestamps específicos
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        } elseif ($request->status === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }
        
        $order->save();

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Status do pedido atualizado com sucesso!');
    }

    // ============================================
    // MÉTODOS DE CUPONS
    // ============================================

    public function createCoupon()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        return view('admin.coupons.create');
    }

    public function storeCoupon(Request $request)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:percent,fixed',
            'discount' => 'required|numeric|min:0',
            'trigger_type' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'max_uses' => 'nullable|integer|min:1',
            'minimum_cart_value' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after:today',
            'applicable_genres' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['is_auto_generated'] = false;
            $data['used_count'] = 0;
            $data['generated_at'] = now();
            
            if (isset($data['applicable_genres']) && is_array($data['applicable_genres'])) {
                $data['applicable_genres'] = json_encode($data['applicable_genres']);
            }

            Coupon::createCoupon($data);

            return redirect()->route('admin.coupons')
                ->with('success', 'Cupom criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar cupom: ' . $e->getMessage());
        }
    }

    public function editCoupon($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $coupon = Coupon::findCoupon($id);
        if (!$coupon) {
            return redirect()->route('admin.coupons')->with('error', 'Cupom não encontrado.');
        }

        return view('admin.coupons.edit', compact('coupon'));
    }

    public function updateCoupon(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'type' => 'required|in:percent,fixed',
            'discount' => 'required|numeric|min:0',
            'trigger_type' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'max_uses' => 'nullable|integer|min:1',
            'minimum_cart_value' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
            'applicable_genres' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        try {
            $data = $request->all();
            
            if (isset($data['applicable_genres']) && is_array($data['applicable_genres'])) {
                $data['applicable_genres'] = json_encode($data['applicable_genres']);
            }

            Coupon::updateCoupon($id, $data);

            return redirect()->route('admin.coupons')
                ->with('success', 'Cupom atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar cupom: ' . $e->getMessage());
        }
    }

    public function destroyCoupon($id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        try {
            Coupon::deleteCoupon($id);
            return redirect()->route('admin.coupons')
                ->with('success', 'Cupom excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.coupons')
                ->with('error', 'Erro ao excluir cupom: ' . $e->getMessage());
        }
    }

    public function toggleCouponStatus(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        try {
            $isActive = $request->input('is_active', true);
            Coupon::updateCoupon($id, ['is_active' => $isActive]);
            
            $status = $isActive ? 'ativado' : 'desativado';
            return redirect()->route('admin.coupons')
                ->with('success', "Cupom {$status} com sucesso!");
        } catch (\Exception $e) {
            return redirect()->route('admin.coupons')
                ->with('error', 'Erro ao alterar status do cupom: ' . $e->getMessage());
        }
    }

    public function generateSmartCoupons(Request $request)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/')->with('error', 'Acesso não autorizado.');
        }

        try {
            // Simular geração de cupons inteligentes
            $generatedCount = 0;
            
            // Gerar cupons baseados em diferentes triggers
            $triggers = [
                'first_purchase' => 'FIRST20',
                'cart_abandonment' => 'COMEBACK15',
                'loyalty' => 'LOYAL25'
            ];

            foreach ($triggers as $trigger => $baseCode) {
                $code = $baseCode . rand(100, 999);
                $discount = rand(10, 30);
                
                $couponData = [
                    'code' => $code,
                    'type' => 'percent',
                    'discount' => $discount,
                    'trigger_type' => $trigger,
                    'max_uses' => rand(50, 200),
                    'is_active' => true,
                    'is_auto_generated' => true,
                    'generated_at' => now(),
                    'expires_at' => now()->addMonths(3)->toDateString()
                ];

                Coupon::createCoupon($couponData);
                $generatedCount++;
            }

            return redirect()->route('admin.coupons')
                ->with('success', "Sucesso! {$generatedCount} cupons inteligentes foram gerados automaticamente.");
        } catch (\Exception $e) {
            return redirect()->route('admin.coupons')
                ->with('error', 'Erro ao gerar cupons inteligentes: ' . $e->getMessage());
        }
    }
}