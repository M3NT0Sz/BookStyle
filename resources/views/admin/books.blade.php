@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Voltar para o Dashboard</a>
    <h2>Livros cadastrados</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Cadastrado por</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td><a href="{{ route('admin.books.show', $book->id) }}">{{ $book->title }}</a></td>
                    <td>{{ $book->author }}</td>
                    <td>
                        @php
                            $user = \App\Models\User::find($book->user_id);
                        @endphp
                        {{ $user ? $user->name : 'Desconhecido' }}
                    </td>
                    <td>
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este livro?')">Deletar</button>
                        </form>
                        <a href="{{ route('admin.books.show', $book->id) }}" class="btn btn-info btn-sm">Ver</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
