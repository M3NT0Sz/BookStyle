@extends('layouts.app')

@section('content')
    @vite('resources/css/cart.css')
    @include('components.nav_bar')
    <div class="cart-container">
        <h1 class="cart-title">Meu Carrinho</h1>
        @php
            $cartCoupon = session('cart.coupon', null);
            $total = 0;
        @endphp
        @if(count($books) > 0)
            <div class="cart-table-wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Livro</th>
                            <th>Autor</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            @php
                                $images = is_array($book['images']) ? $book['images'] : json_decode($book['images'], true);
                                $subtotal = $book['price'] * $book['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>
                                    @if(is_array($images) && !empty($images))
                                        <img class="cart-img" src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book['name'] }}">
                                    @elseif(!is_array($images) && !empty($images))
                                        <img class="cart-img" src="{{ asset('storage/' . $images) }}" alt="{{ $book['name'] }}">
                                    @endif
                                </td>
                                <td>{{ $book['name'] }}</td>
                                <td>{{ $book['author'] }}</td>
                                <td>{{ $book['quantity'] }}</td>
                                <td>R$ {{ number_format($book['price'], 2, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $book['id']) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="cart-remove-btn" type="submit">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="cart-summary-coupon-row">
                <div class="cart-summary">
                    @php
                        $cartCoupon = session('cart.coupon', null);
                    @endphp
                    @if($cartCoupon)
                        <div class="cart-coupon-info">
                            <strong>Cupom aplicado:</strong> <span>{{ $cartCoupon['code'] }}</span> <span class="cart-coupon-discount">({{ $cartCoupon['type'] == 'percent' ? $cartCoupon['discount'] . '%' : 'R$' . $cartCoupon['discount'] }})</span>
                        </div>
                    @endif
                    <p><strong>Total sem desconto:</strong> <span>R$ {{ number_format($total, 2, ',', '.') }}</span></p>
                    @if($cartCoupon)
                        @php
                            $discount = $cartCoupon['type'] == 'percent' ? ($total * ($cartCoupon['discount'] / 100)) : $cartCoupon['discount'];
                            $discountedTotal = max($total - $discount, 0);
                        @endphp
                        <p><strong>Desconto:</strong> <span class="cart-discount">-R$ {{ number_format($discount, 2, ',', '.') }}</span></p>
                        <p><strong>Total com desconto:</strong> <span class="cart-total">R$ {{ number_format($discountedTotal, 2, ',', '.') }}</span></p>
                    @endif
                </div>
                <div class="cart-coupon-form-wrapper">
                    <form class="cart-coupon-form" action="{{ route('cart.applyCoupon') }}" method="POST">
                        @csrf
                        <label for="coupon_code">Adicionar cupom:</label>
                        <input type="text" id="coupon_code" name="coupon_code" placeholder="Digite o código do cupom" required>
                        <button type="submit">Aplicar</button>
                    </form>
                    @if(session('coupon_error'))
                        <div class="cart-coupon-error">{{ session('coupon_error') }}</div>
                    @endif
                    @if(session('coupon_success'))
                        <div class="cart-coupon-success">{{ session('coupon_success') }}</div>
                    @endif
                </div>
            </div>
            <form class="cart-clear-form" action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button class="cart-clear-btn" type="submit">Esvaziar Carrinho</button>
            </form>
        @else
            <p class="cart-empty">Seu carrinho está vazio.</p>
        @endif
        <div class="cart-actions">
            <a class="cart-link" href="{{ route('books.index') }}">Continuar comprando</a>
            <a class="cart-link" href="{{ route('index') }}">Voltar</a>
        </div>
    </div>
    <div style="height: 60px;"></div>
    @include('components.footer')
@endsection
