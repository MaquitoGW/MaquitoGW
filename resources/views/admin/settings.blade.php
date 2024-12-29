@extends('layouts.admin')
@section('title', 'Configurações')
@section('content')

    <form method="POST" action="{{ route('update.auth') }}" class="options-set">
        @csrf
        <h3>Configurações de conta</h3>
        <label for="name">Nome</label>
        <input type="text" id="name" name="name" placeholder="Name" value="{{ Auth::user()->name }}" />
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="example@email.com" value="{{ Auth::user()->email }}" />
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" placeholder="*********" />
        <button type="submit">Atualizar</button>
    </form>

    <form method="POST" action="{{ route('settings.update') }}" class="options-set">
        @csrf
        <h3>Configurações do servidor</h3>
        <label for="app-name">Nome do Servidor</label>
        <input type="text" id="app-name" name="app-name" value="{{ env('APP_TITLE') }}" />

        <label for="app-env">Modo de Ambiente</label>
        <select id="app-env" name="app-env">
            <option value="local" {{ env('APP_ENV') == 'local' ? 'selected' : '' }}>Local</option>
            <option value="production" {{ env('APP_ENV') == 'production' ? 'selected' : '' }}>Produção</option>
            <option value="staging" {{ env('APP_ENV') == 'staging' ? 'selected' : '' }}>Estágio</option>
        </select>

        <label for="app-debug">Modo de Depuração</label>
        <select id="app-debug" name="app-debug">
            <option value="true" {{ env('APP_DEBUG') == 'true' ? 'selected' : '' }}>Ativado</option>
            <option value="false" {{ env('APP_DEBUG') == 'false' ? 'selected' : '' }}>Desativado</option>
        </select>

        <label for="app-timezone">Fuso Horário</label>
        <select id="app-timezone" name="app-timezone">
            <option value="UTC" {{ env('APP_TIMEZONE') == 'UTC' ? 'selected' : '' }}>UTC</option>
            <option value="America/Sao_Paulo" {{ env('APP_TIMEZONE') == 'America/Sao_Paulo' ? 'selected' : '' }}>Horário de
                Brasília (BRT) - UTC-03:00</option>
            <option value="America/Fortaleza" {{ env('APP_TIMEZONE') == 'America/Fortaleza' ? 'selected' : '' }}>Horário do
                Nordeste (BRT) - UTC-03:00</option>
            <option value="America/Manaus" {{ env('APP_TIMEZONE') == 'America/Manaus' ? 'selected' : '' }}>Horário da
                Região Norte (AMT) - UTC-04:00</option>
            <option value="America/Cuiaba" {{ env('APP_TIMEZONE') == 'America/Cuiaba' ? 'selected' : '' }}>Horário do
                Centro-Oeste (AMT) - UTC-04:00</option>
            <option value="America/Porto_Velho" {{ env('APP_TIMEZONE') == 'America/Porto_Velho' ? 'selected' : '' }}>
                Horário de Rondônia (AMT) - UTC-04:00</option>
            <option value="America/New_York" {{ env('APP_TIMEZONE') == 'America/New_York' ? 'selected' : '' }}>Horário de
                Nova York (EST) - UTC-05:00</option>
            <option value="America/Toronto" {{ env('APP_TIMEZONE') == 'America/Toronto' ? 'selected' : '' }}>Horário de
                Toronto (EST) - UTC-05:00</option>
            <option value="America/Mexico_City" {{ env('APP_TIMEZONE') == 'America/Mexico_City' ? 'selected' : '' }}>
                Horário da Cidade do México (CST) - UTC-06:00</option>
            <option value="America/Buenos_Aires" {{ env('APP_TIMEZONE') == 'America/Buenos_Aires' ? 'selected' : '' }}>
                Horário de Buenos Aires (ART) - UTC-03:00</option>
            <option value="America/Lima" {{ env('APP_TIMEZONE') == 'America/Lima' ? 'selected' : '' }}>Horário de Lima
                (PET) - UTC-05:00</option>
        </select>

        <label for="app-faker-locale">Idioma Principal</label>
        <select id="app-faker-locale" name="app-faker-locale">
            <option value="en_US" {{ env('APP_FAKER_LOCALE') == 'en_US' ? 'selected' : '' }}>Inglês (EUA)</option>
            <option value="pt_BR" {{ env('APP_FAKER_LOCALE') == 'pt_BR' ? 'selected' : '' }}>Português (BRA)</option>
        </select>

        <label for="multiple-languages">Múltiplos Idiomas</label>
        <select id="multiple-languages" name="multiple-languages">
            <option value="1" {{ env('MULTIPLE_LANGUAGES') == 1 ? 'selected' : '' }}>Ativado</option>
            <option value="0" {{ env('MULTIPLE_LANGUAGES') == 0 ? 'selected' : '' }}>Desativado</option>
        </select>

        <button type="submit">Atualizar</button>
    </form>

@endsection
