@extends('layouts.app')

@section('content')
    <h1>Cupons de Desconto</h1>
    <a href="{{ route('coupons.create') }}">Novo Cupom</a>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <table>
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
                    <td>{{ $coupon['discount'] }}</td>
                    <td>{{ $coupon['type'] == 'percent' ? 'Porcentagem' : 'Valor Fixo' }}</td>
                    <td>{{ $coupon['expires_at'] }}</td>
                    <td>
                        <a href="{{ route('coupons.edit', $coupon['id']) }}">Editar</a>
                        <form action="{{ route('coupons.destroy', $coupon['id']) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('index') }}">Voltar</a>
@endsection