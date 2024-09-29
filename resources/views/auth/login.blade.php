@extends('layouts.auth')
@section('title', 'MaquitoGW || Portifólio::Login')
@section('content')
    <form class="login-form" action="{{ route('login') }}" method="post">
        <h2>Faça seu login <b>˚.⋆</b></h2>
        @csrf

        <div class="form-group">
            <label for="username">email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">senha</label>
            <input type="password" name="password" required>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <span class="error-message">{{ $error }}</span>
            @endforeach
        @endif

        <button type="submit" class="button">Entrar</button>
    </form>
@endsection
