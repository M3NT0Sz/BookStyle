@extends('layouts.app')

@section('content')
    <h1>Meu Carrinho</h1>
    @php
        $cartCoupon = session('cart.coupon', null);
        $total = 0;
    @endphp
    @if($cartCoupon)
        <p><strong>Cupom aplicado:</strong> {{ $cartCoupon['code'] }} ({{ $cartCoupon['type'] == 'percent' ? $cartCoupon['discount'] . '%' : 'R$' . $cartCoupon['discount'] }})</p>
    @endif
    @if(count($books) > 0)
        <table>
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
                                <img width="60" src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book['name'] }}">
                            @elseif(!is_array($images) && !empty($images))
                                <img width="60" src="{{ asset('storage/' . $images) }}" alt="{{ $book['name'] }}">
                            @endif
                        </td>
                        <td>{{ $book['name'] }}</td>
                        <td>{{ $book['author'] }}</td>
                        <td>{{ $book['quantity'] }}</td>
                        <td>R$ {{ number_format($book['price'], 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $book['id']) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Total sem desconto:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
        @if($cartCoupon)
            @php
                $discount = $cartCoupon['type'] == 'percent' ? ($total * ($cartCoupon['discount'] / 100)) : $cartCoupon['discount'];
                $discountedTotal = max($total - $discount, 0);
            @endphp
            <p><strong>Desconto:</strong> -R$ {{ number_format($discount, 2, ',', '.') }}</p>
            <p><strong>Total com desconto:</strong> R$ {{ number_format($discountedTotal, 2, ',', '.') }}</p>
        @endif
        <form action="{{ route('cart.clear') }}" method="POST">
            @csrf
            <button type="submit">Esvaziar Carrinho</button>
        </form>
    @else
        <p>Seu carrinho está vazio.</p>
    @endif
    <a href="{{ route('books.index') }}">Continuar comprando</a>
    <a href="{{ route('index') }}">Voltar</a>
@endsection
