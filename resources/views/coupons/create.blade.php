@extends('layouts.app')

@section('content')
    <h1>Novo Cupom</h1>
    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf
        <label for="code">Código:</label>
        <input type="text" name="code" id="code" required>
        <label for="discount">Desconto:</label>
        <input type="number" name="discount" id="discount" required step="0.01">
        <label for="type">Tipo:</label>
        <select name="type" id="type" required>
            <option value="percent">Porcentagem (%)</option>
            <option value="fixed">Valor Fixo (R$)</option>
        </select>
        <label for="expires_at">Validade:</label>
        <input type="date" name="expires_at" id="expires_at">
        <button type="submit">Salvar</button>
    </form>
    <a href="{{ route('coupons.index') }}">Voltar</a>
@endsection
