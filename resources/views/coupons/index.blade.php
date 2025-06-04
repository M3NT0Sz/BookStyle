@extends('layouts.app')

@section('content')
    @vite('resources/css/coupons.css')
    <div class="coupons-container">
        <h1 class="coupons-title">Cupons de Desconto</h1>
        <a class="coupons-btn" href="{{ route('coupons.create') }}">Novo Cupom</a>
        @if(session('success'))
            <div class="coupons-success">{{ session('success') }}</div>
        @endif
        <div class="coupons-table-wrapper">
            <table class="coupons-table">
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
                            <td>{{ $coupon['code'] }}</td>
                            <td>{{ $coupon['type'] == 'percent' ? $coupon['discount'] . '%' : 'R$' . number_format($coupon['discount'], 2, ',', '.') }}</td>
                            <td>{{ $coupon['type'] == 'percent' ? 'Porcentagem' : 'Valor Fixo' }}</td>
                            <td>{{ $coupon['expires_at'] ? date('d/m/Y', strtotime($coupon['expires_at'])) : '-' }}</td>
                            <td>
                                <div class="coupons-actions">
                                    <a class="coupons-btn" href="{{ route('coupons.edit', $coupon['id']) }}">Editar</a>
                                    <form action="{{ route('coupons.destroy', $coupon['id']) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="coupons-btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a class="coupons-link" href="{{ route('index') }}">Voltar</a>
    </div>
@endsection