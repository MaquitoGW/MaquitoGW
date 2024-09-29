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
                <li @if ($selected == 1) aria-selected="true" @endif meta-click="dashboard">
                    <i class="fa fa-solid fa-server"></i>
                    <label>Dashboard</label>
                </li>
                <li @if ($selected == 2) aria-selected="true" @endif meta-click="projects">
                    <i class="fa-sharp fa-regular fa-code"></i>
                    <label>Projetos</label>
                </li>
                <li @if ($selected == 3) aria-selected="true" @endif meta-click="skills">
                    <i class="fa-solid fa-star"></i>
                    <label>Habilidades</label>
                </li>
                <li @if ($selected == 4) aria-selected="true" @endif meta-click="info">
                    <i class="fa-solid fa-circle-info"></i>
                    <label>Informações</label>
                </li>
                <li @if ($selected == 5) aria-selected="true" @endif meta-click="users">
                    <i class="fa fa-solid fa-users"></i>
                    <label>Usuários</label>
                </li>
            </ul>
        </nav>
        <section>
            @yield('content')
            <footer>
                {{ env('APP_NAME') }} @ {{ date('Y') }} | <a href="{{ env('APP_URL') }}"
                    target="_blank">{{ env('APP_DOMINE') }}</a>
            </footer>
        </section>

        {{-- Mensagem de alerta  --}}
        @if (session('success'))
            <div class="popup">
                <span class="close">&times;</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif
    </main>
    <script src="/js/painel.js"></script>
</body>

</html>
