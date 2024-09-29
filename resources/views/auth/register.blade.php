@extends('layouts.auth')
@section('title', 'MaquitoGW || Portifólio::Register')
@section('content')
    <form class="login-form" action="{{ route('register') }}" method="post">
        <h2>Fazer registro <b>˚.⋆</b></h2>
        @csrf
        <div class="form-group">
            <label for="username">usuário</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">senha</label>
            <input type="password" name="password" required>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <span class="erroLogin">{{ $error }}</span>
            @endforeach
        @endif

        <button type="submit" class="button">Entrar</button>
    </form>
@endsection
