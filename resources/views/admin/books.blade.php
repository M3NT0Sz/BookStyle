@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Voltar para o Dashboard</a>
    <div class="mb-3">
        <a href="{{ route('admin.books.export', 'json') }}" class="btn btn-success">Exportar JSON</a>
        <a href="{{ route('admin.books.export', 'csv') }}" class="btn btn-primary">Exportar CSV</a>
    </div>
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
                @php
                    $isArr = is_array($book);
                    $id = $isArr && isset($book['id']) ? $book['id'] : ($book->id ?? '');
                    $title = $isArr && isset($book['title']) ? $book['title'] : ($book->title ?? '');
                    $author = $isArr && isset($book['author']) ? $book['author'] : ($book->author ?? '');
                    $userId = $isArr && isset($book['user_id']) ? $book['user_id'] : ($book->user_id ?? null);
                    $user = $userId ? \App\Models\User::find($userId) : null;
                @endphp
                <tr>
                    <td>{{ $id }}</td>
                    <td><a href="{{ route('admin.books.show', $id) }}">{{ $title }}</a></td>
                    <td>{{ $author }}</td>
                    <td>
                        @if($user)
                            {{ is_array($user) ? ($user['name'] ?? 'Desconhecido') : $user->name }}
                        @else
                            Desconhecido
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.books.destroy', $id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este livro?')">Deletar</button>
                        </form>
                        <a href="{{ route('admin.books.show', $id) }}" class="btn btn-info btn-sm">Ver</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
