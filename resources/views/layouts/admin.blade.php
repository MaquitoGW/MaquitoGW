<!DOCTYPE html>
<html lang="pt-br">

<head>
    @php
        $adminUser = Auth::user();
        $adminInitials = collect(explode(' ', trim($adminUser->name ?? 'User')))
            ->filter()
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Controle || @yield('title')</title>
    <link rel="stylesheet" href="/css/painel.css">
    <link rel="stylesheet" href="/css/theme.css">
    @include('partials.favicon')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karla">
    <link rel="stylesheet" href="/css/icons.css">
    <script src="/js/icons.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Montserrat:wght@300;400;600;700&family=Roboto:wght@300;400;700&family=Lobster&family=Playfair+Display:wght@400;700&family=Cinzel:wght@400;700&family=Raleway:wght@300;400;600&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="/css/logocreate.css">
    @include('partials.theme-config')
    <style>
        body[data-app-theme-effective="light"] {
            --background-color: #f6f8fa !important;
            --bg-sidebar: #ffffff !important;
            --card-bg: #ffffff !important;
            --active-bg: #f6f8fa !important;
            --text-light: #24292f !important;
            --text-secondary: #57606a !important;
            --white-color: #1f2328 !important;
            --border-color: #d0d7de !important;
            --surface-muted: #f6f8fa !important;
            --surface-hover: #eef1f4 !important;
            --input-bg: #ffffff !important;
            --border: #d0d7de !important;
            --foreground: #24292f !important;
            --muted-foreground: #57606a !important;
        }
    </style>
</head>

<body>
    <header class="admin-topbar">
        <div class="admin-brand">
            <button class="icon-button sidebar-toggle" type="button" aria-label="Alternar menu" title="Alternar menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="name">
                <h3>{{ env('APP_TITLE', 'Painel') }}</h3>
                <span>Painel administrativo</span>
            </div>
        </div>

        <div class="name-section">@yield('title')</div>

        <div class="topbar-actions">
            <div class="theme-switcher" aria-label="Tema do painel">
                <button type="button" data-admin-theme-option="light" title="Tema claro"><i class="fa-solid fa-sun"></i></button>
                <button type="button" data-admin-theme-option="dark" title="Tema escuro"><i class="fa-solid fa-moon"></i></button>
                <button type="button" data-admin-theme-option="system" title="Tema do sistema"><i class="fa-solid fa-desktop"></i></button>
            </div>

            <div class="user-login">
                <div class="admin-avatar" aria-hidden="true">{{ $adminInitials }}</div>
                <div class="info">
                    <strong>{{ $adminUser->name }}</strong>
                    <span>{{ $adminUser->email }}</span>
                </div>
                <a class="logout-button" href="{{ route('logout') }}" title="Sair">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </header>

    <main>
        <nav class="navBar">
            <ul>
                <li @if (Route::currentRouteName() == 'dashboard') aria-selected="true" @endif meta-click="dashboard" title="Dashboard">
                    <i class="fa fa-solid fa-server"></i>
                    <label>Dashboard</label>
                </li>
                <li @if (Route::currentRouteName() == 'projects') aria-selected="true" @endif meta-click="projects" title="Projetos">
                    <i class="fa-sharp fa-regular fa-code"></i>
                    <label>Projetos</label>
                </li>
                <li @if (Route::currentRouteName() == 'skills') aria-selected="true" @endif meta-click="skills" title="Habilidades">
                    <i class="fa-solid fa-star"></i>
                    <label>Habilidades</label>
                </li>
                <li @if (Route::currentRouteName() == 'info') aria-selected="true" @endif meta-click="info" title="Informacoes">
                    <i class="fa-solid fa-circle-info"></i>
                    <label>Informacoes</label>
                </li>
                <li @if (Route::currentRouteName() == 'users') aria-selected="true" @endif meta-click="users" title="Usuarios">
                    <i class="fa fa-solid fa-users"></i>
                    <label>Usuarios</label>
                </li>
                <li @if (Route::currentRouteName() == 'customization') aria-selected="true" @endif meta-click="customization" title="Personalizacao">
                    <i class="fa-regular fa-wand-magic-sparkles"></i>
                    <label>Personalizacao</label>
                </li>
                <li @if (Route::currentRouteName() == 'links' || Route::currentRouteName() == 'links.new' || Route::currentRouteName() == 'links.edit') aria-selected="true" @endif meta-click="links" title="Meus Links">
                    <i class="fa-solid fa-link"></i>
                    <label>Meus Links</label>
                </li>
                <li @if (Route::currentRouteName() == 'experiences' || Route::currentRouteName() == 'experiences.new' || Route::currentRouteName() == 'experiences.edit') aria-selected="true" @endif meta-click="experiences" title="Experiencias">
                    <i class="fa-solid fa-briefcase"></i>
                    <label>Experiencias</label>
                </li>
                <li @if (Route::currentRouteName() == 'contacts') aria-selected="true" @endif meta-click="contacts" title="Mensagens">
                    <i class="fa-solid fa-envelope"></i>
                    <label>Mensagens</label>
                </li>
                <li @if (Route::currentRouteName() == 'settings') aria-selected="true" @endif meta-click="settings" title="Configuracoes">
                    <i class="fa fa-solid fa-gear"></i>
                    <label>Configuracoes</label>
                </li>
            </ul>

            <footer>
                {{ env('APP_TITLE') }} @ {{ date('Y') }} | <a href="{{ env('APP_URL') }}" target="_blank">{{ env('APP_DOMINE') }}</a>
            </footer>
        </nav>
        <section>
            @yield('content')
        </section>

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
    <script src="/js/theme.js"></script>
    <script src="/js/painel.js"></script>
</body>

</html>
