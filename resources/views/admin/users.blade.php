@extends('layouts.admin')
@section('title', 'Usuários')
@section('content')

    <div class="options-list">
        <h3>Opções</h3>
        <a href="{{ route('register') }}"> Registrar usuário</a>
    </div>

    <div class="lists">
        <div class="list">
            <ul>
                @foreach ($users as $user)
                    <li>
                        <label class="username">{{ $user['name'] }}</label>
                        <span>{{ $user['email'] }}</span>

                        <div class="options">
                            <a href="{{ route('delete') }}/{{ $user['id'] }}">Remover usuário</a>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

@endsection
