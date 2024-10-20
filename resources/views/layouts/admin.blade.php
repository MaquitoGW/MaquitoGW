<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Controle || @yield('title')</title>
    <link rel="stylesheet" href="/css/painel.css">
    <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karla">
    <link rel="stylesheet" href="/icons/css/all.css">
    <script src="/icons/js/all.js"></script>
</head>

<body>
    <header>
        <div class="name">
            <h3>Painel de Controle</h3>
        </div>
        <div class="name-section">/ @yield('title')</div>

        <div class="user-login">
            <div class="info">
                <label>Olá <b>{{ Auth::user()->name }}</b>,Seja Bem vindo!</label>
                <span>{{ Auth::user()->email }}</span>
            </div>
            <a href="{{ route('logout') }}">Sair</a>
        </div>
    </header>

    <main>
        <nav class="navBar">
            <ul>
                <li @if (Route::currentRouteName() == 'dashboard') aria-selected="true" @endif meta-click="dashboard">
                    <i class="fa fa-solid fa-server"></i>
                    <label>Dashboard</label>
                </li>
                <li @if (Route::currentRouteName() == 'projects') aria-selected="true" @endif meta-click="projects">
                    <i class="fa-sharp fa-regular fa-code"></i>
                    <label>Projetos</label>
                </li>
                <li @if (Route::currentRouteName() == 'skills') aria-selected="true" @endif meta-click="skills">
                    <i class="fa-solid fa-star"></i>
                    <label>Habilidades</label>
                </li>
                <li @if (Route::currentRouteName() == 'info') aria-selected="true" @endif meta-click="info">
                    <i class="fa-solid fa-circle-info"></i>
                    <label>Informações</label>
                </li>
                <li @if (Route::currentRouteName() == 'users') aria-selected="true" @endif meta-click="users">
                    <i class="fa fa-solid fa-users"></i>
                    <label>Usuários</label>
                </li>
                <li @if (Route::currentRouteName() == 'customization') aria-selected="true" @endif meta-click="customization">
                    <i class="fa-regular fa-wand-magic-sparkles"></i>
                    <label>Personalização</label>
                </li>
                <li @if (Route::currentRouteName() == 'settings') aria-selected="true" @endif meta-click="settings">
                    <i class="fa fa-solid fa-gear"></i>
                    <label>Configurações</label>
                </li>
            </ul>

            <footer>
                {{ env('APP_NAME') }} @ {{ date('Y') }} | <a href="{{ env('APP_URL') }}"
                    target="_blank">{{ env('APP_DOMINE') }}</a>
            </footer>
        </nav>
        <section>
            @yield('content')
        </section>

        {{-- Mensagem de alerta  --}}
        @if (session('success'))
            <div id="popup" class="popup success">
                <span id="close" class="close"><i class="fa-regular fa-xmark"></i></span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('err'))
            <div id="popup" class="popup err">
                <span id="close" class="close"><i class="fa-regular fa-xmark"></i></span>
                <p>{{ session('err') }}</p>
            </div>
        @endif
    </main>
    <script src="/js/painel.js"></script>
</body>

</html>
