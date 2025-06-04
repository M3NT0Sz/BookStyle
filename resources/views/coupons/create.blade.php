@extends('layouts.app')

@section('content')
    @vite('resources/css/coupons_form.css')
    <div class="coupons-form-container">
        <h1 class="coupons-form-title">Novo Cupom</h1>
        @if ($errors->any())
            <div class="coupons-form-error">
                <ul style="margin:0; padding-left: 1.2em; text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="coupons-form" action="{{ route('coupons.store') }}" method="POST">
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
            <div class="coupons-form-btns">
                <button type="submit" class="coupons-form-btn">Salvar</button>
                <a href="{{ route('coupons.index') }}" class="coupons-form-btn-back">Voltar</a>
            </div>
        </form>
    </div>
@endsection
