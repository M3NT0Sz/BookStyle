@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">Voltar para o Dashboard</a>
    <h2>Usuários cadastrados</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ is_array($user) ? $user['id'] : $user->id }}</td>
                    <td>{{ is_array($user) ? $user['name'] : $user->name }}</td>
                    <td>{{ is_array($user) ? $user['email'] : $user->email }}</td>
                    <td>{{ (is_array($user) ? $user['is_admin'] : $user->is_admin) ? 'Admin' : 'Cliente' }}</td>
                    <td>
                        <!-- Exemplo de ações, ajuste as rotas conforme necessário -->
                        <a href="{{ route('admin.users.edit', is_array($user) ? $user['id'] : $user->id) }}" class="btn btn-info btn-sm">Editar</a>
                        <form action="#" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" disabled>Remover</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
