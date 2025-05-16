<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:coupons,code',
            'discount' => 'required|numeric',
            'type' => 'required|in:percent,fixed',
            'expires_at' => 'nullable|date',
        ]);
        Coupon::create($data);
        return redirect()->route('coupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit($id)
    {
        $coupon = Coupon::find($id);
        return view('coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'required',
            'discount' => 'required|numeric',
            'type' => 'required|in:percent,fixed',
            'expires_at' => 'nullable|date',
        ]);
        Coupon::update($id, $data);
        return redirect()->route('coupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Coupon::delete($id);
        return redirect()->route('coupons.index')->with('success', 'Cupom removido com sucesso!');
    }
}
