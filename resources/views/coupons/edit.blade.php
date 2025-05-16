@extends('layouts.app')

@section('content')
    <h1>Editar Cupom</h1>
    <form action="{{ route('coupons.update', $coupon['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="code">Código:</label>
        <input type="text" name="code" id="code" value="{{ $coupon['code'] }}" required>
        <label for="discount">Desconto:</label>
        <input type="number" name="discount" id="discount" value="{{ $coupon['discount'] }}" required step="0.01">
        <label for="type">Tipo:</label>
        <select name="type" id="type" required>
            <option value="percent" {{ $coupon['type'] == 'percent' ? 'selected' : '' }}>Porcentagem (%)</option>
            <option value="fixed" {{ $coupon['type'] == 'fixed' ? 'selected' : '' }}>Valor Fixo (R$)</option>
        </select>
        <label for="expires_at">Validade:</label>
        <input type="date" name="expires_at" id="expires_at" value="{{ $coupon['expires_at'] }}">
        <button type="submit">Salvar</button>
    </form>
    <a href="{{ route('coupons.index') }}">Voltar</a>
@endsection
