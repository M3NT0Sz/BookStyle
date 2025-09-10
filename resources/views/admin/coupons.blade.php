@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Voltar para o Dashboard</a>
    <a href="{{ route('coupons.create') }}" class="btn btn-success mb-3">Criar cupom</a>
    <h2>Cupons cadastrados</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Desconto</th>
                <th>Tipo</th>
                <th>Validade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td>{{ is_array($coupon) ? $coupon['code'] : $coupon->code }}</td>
                    <td>{{ (is_array($coupon) ? $coupon['type'] : $coupon->type) == 'percent' ? (is_array($coupon) ? $coupon['discount'] : $coupon->discount) . '%' : 'R$' . number_format((is_array($coupon) ? $coupon['discount'] : $coupon->discount), 2, ',', '.') }}</td>
                    <td>{{ (is_array($coupon) ? $coupon['type'] : $coupon->type) == 'percent' ? 'Porcentagem' : 'Valor Fixo' }}</td>
                    <td>{{ (is_array($coupon) ? $coupon['expires_at'] : $coupon->expires_at) ? date('d/m/Y', strtotime(is_array($coupon) ? $coupon['expires_at'] : $coupon->expires_at)) : '-' }}</td>
                    <td>
                        <a href="{{ route('coupons.edit', is_array($coupon) ? $coupon['id'] : $coupon->id) }}" class="btn btn-info btn-sm">Editar</a>
                        <form action="{{ route('coupons.destroy', is_array($coupon) ? $coupon['id'] : $coupon->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este cupom?')">Remover</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
