@extends('layouts.app')

@section('content')
<form action="{{ route('login') }}" method="post">
    @csrf
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}">
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div>
        <button type="submit">Login</button>
    </div>
    <div>
        <a href="{{ route('password.request') }}">Forgot your password?</a>
    </div>
    <div>
        <a href="{{ route('register') }}">Register</a>
    </div>
</form>
@endsection