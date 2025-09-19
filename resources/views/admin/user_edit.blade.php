@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('admin.users') }}" class="btn btn-secondary mb-3">Voltar para Usuários</a>
    <h2>Editar status de administrador</h2>
    <form action="{{ route('admin.users.update', (is_array($user) ? $user['id'] : $user->id)) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="is_admin">Tipo de usuário:</label>
            <select name="is_admin" id="is_admin" class="form-control">
                <option value="0" {{ !(is_array($user) ? $user['is_admin'] : $user->is_admin) ? 'selected' : '' }}>Cliente</option>
                <option value="1" {{ (is_array($user) ? $user['is_admin'] : $user->is_admin) ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection
