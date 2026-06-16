@php
    $displayName = $user->links_display_name ?: $user->name;
    $bio = $user->links_bio ?: 'Todos os meus links em um único lugar.';
    $initial = strtoupper(substr($displayName, 0, 1));

    $iconClass = function ($icon, $url = '') {
        $value = strtolower(trim((string) $icon));
        $target = strtolower($url);

        if (str_starts_with($value, 'fa-')) {
            return $value;
        }

        $known = [
            'facebook' => 'fa-brands fa-facebook',
            'twitter' => 'fa-brands fa-x-twitter',
            'x' => 'fa-brands fa-x-twitter',
            'instagram' => 'fa-brands fa-instagram',
            'linkedin' => 'fa-brands fa-linkedin',
            'github' => 'fa-brands fa-github',
            'youtube' => 'fa-brands fa-youtube',
            'tiktok' => 'fa-brands fa-tiktok',
            'whatsapp' => 'fa-brands fa-whatsapp',
            'telegram' => 'fa-brands fa-telegram',
            'email' => 'fa-solid fa-envelope',
            'mail' => 'fa-solid fa-envelope',
            'website' => 'fa-solid fa-globe',
            'site' => 'fa-solid fa-globe',
            'portfolio' => 'fa-solid fa-briefcase',
        ];

        foreach ($known as $key => $class) {
            if ($value === $key || str_contains($target, $key)) {
                return $class;
            }
        }

        return 'fa-solid fa-link';
    };
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $displayName }} - Meus Links</title>
    @include('partials.favicon')
    <link rel="stylesheet" href="/css/icons.css">
    <link rel="stylesheet" href="/css/theme.css">
    <script src="/js/icons.js"></script>
    @include('partials.theme-config')
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: var(--background-color, #0d1117);
            --surface: var(--card-bg, #161b22);
            --link-surface-muted: var(--surface-muted, #010409);
            --border: var(--border-color, #30363d);
            --text: var(--text-light, #c9d1d9);
            --muted: var(--text-secondary, #8b949e);
            --white: var(--white-color, #f0f6fc);
            --blue: var(--primary-color, #58a6ff);
            --green: var(--primary-color, #238636);
            --shadow: 0 20px 60px rgba(1, 4, 9, .55);
        }

        body[data-app-theme-effective="light"] {
            --bg: #f6f8fa;
            --surface: #ffffff;
            --link-surface-muted: #eef1f4;
            --border: #d0d7de;
            --text: #24292f;
            --muted: #57606a;
            --white: #1f2328;
            --blue: var(--primary-color, #0969da);
            --green: var(--primary-dark, #1a7f37);
            --links-hover: #f3f4f6;
            --shadow: 0 16px 36px rgba(140, 149, 159, .2);
        }

        body[data-app-theme-effective="dark"] {
            --bg: #0d1117;
            --surface: #161b22;
            --link-surface-muted: #010409;
            --border: #30363d;
            --text: #c9d1d9;
            --muted: #8b949e;
            --white: #f0f6fc;
            --blue: var(--primary-color, #58a6ff);
            --green: var(--primary-color, #238636);
            --links-hover: #1c2128;
            --shadow: 0 20px 60px rgba(1, 4, 9, .55);
        }

        body {
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, var(--theme-radial-medium), transparent 28rem),
                radial-gradient(circle at bottom right, rgba(var(--theme-secondary-rgb), .10), transparent 26rem),
                var(--bg);
            color: var(--text);
            padding: 24px;
        }

        .page {
            width: min(640px, 100%);
            margin: 0 auto;
            animation: pageIn .55s ease both;
        }

        .links-theme-toggle {
            width: 42px;
            height: 42px;
            margin: 0 0 14px auto;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: var(--surface);
            color: var(--text);
            display: grid;
            place-items: center;
            box-shadow: 0 8px 20px rgba(1, 4, 9, .08);
        }

        .profile-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .banner {
            height: 160px;
            background:
                linear-gradient(135deg, var(--theme-radial-strong), rgba(var(--theme-secondary-rgb), .14)),
                var(--link-surface-muted);
        }

        .banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile {
            padding: 0 24px 24px;
            text-align: center;
            margin-top: -48px;
        }

        .avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 4px solid var(--surface);
            margin: 0 auto 14px;
            background: var(--link-surface-muted);
            display: grid;
            place-items: center;
            color: var(--white);
            font-size: 36px;
            font-weight: 800;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile h1 {
            color: var(--white);
            font-size: 28px;
            line-height: 1.1;
        }

        .profile p {
            color: var(--muted);
            margin-top: 10px;
            line-height: 1.5;
        }

        .links {
            display: grid;
            gap: 12px;
            margin-top: 18px;
        }

        .link-card {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr) 20px;
            gap: 14px;
            align-items: center;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            color: inherit;
            text-decoration: none;
            box-shadow: 0 10px 26px rgba(1, 4, 9, .08);
            animation: linkIn .45s ease both;
            animation-delay: calc(var(--i) * 70ms);
            transition: border-color .16s ease, background .16s ease;
        }

        .link-card:hover {
            border-color: var(--blue);
            background: var(--links-hover);
        }

        body[data-app-theme-effective="dark"] .link-card {
            box-shadow: 0 8px 24px rgba(1, 4, 9, .28);
        }

        body[data-app-theme-effective="light"] .link-card:hover {
            border-color: color-mix(in srgb, var(--blue) 55%, var(--border));
        }

        .link-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: rgba(var(--theme-primary-rgb), .12);
            color: var(--blue);
            font-size: 20px;
        }

        .link-title {
            color: var(--white);
            font-weight: 800;
            font-size: 16px;
        }

        .link-description {
            color: var(--muted);
            font-size: 13px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .link-arrow {
            color: var(--muted);
            transition: transform .16s ease, color .16s ease;
        }

        .link {
            color: var(--blue);
            text-decoration: none;
        }

        .link-card:hover .link-arrow {
            color: var(--blue);
            transform: translateX(3px);
        }

        .empty-state,
        .footer {
            text-align: center;
            color: var(--muted);
        }

        .empty-state {
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 28px;
            background: var(--surface);
        }

        .footer {
            margin-top: 26px;
            font-size: 13px;
        }

        @keyframes pageIn {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes linkIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
</head>

<body>
    <main class="page">
        <button class="theme-toggle links-theme-toggle" type="button" aria-label="Alternar tema">
            <i class="fa-solid fa-moon"></i>
        </button>

        <section class="profile-card">
            <div class="banner">
                @if ($user->links_banner)
                    <img src="{{ $user->links_banner }}" alt="Banner de {{ $displayName }}">
                @endif
            </div>

            <div class="profile">
                <div class="avatar">
                    @if ($user->links_avatar)
                        <img src="{{ $user->links_avatar }}" alt="{{ $displayName }}">
                    @else
                        {{ $initial }}
                    @endif
                </div>
                <h1>{{ $displayName }}</h1>
                <p>{{ $bio }}</p>
            </div>
        </section>

        <section class="links">
            @forelse ($links as $link)
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="link-card"
                    style="--i: {{ $loop->index }}">
                    <div class="link-icon">
                        <i class="{{ $iconClass($link->icon, $link->url) }}"></i>
                    </div>
                    <div>
                        <div class="link-title">{{ $link->title }}</div>
                        @if ($link->description)
                            <div class="link-description">{{ $link->description }}</div>
                        @endif
                    </div>
                    <div class="link-arrow">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>
            @empty
                <div class="empty-state">Nenhum link disponível no momento.</div>
            @endforelse
        </section>

<footer class="footer">
    {{ config('app.name', 'Portfolio') }} • Criado e mantido por <a class="link" href="https://maquitogw.dev">MaquitoGW</a>
</footer>
    </main>
    <script src="/js/theme.js"></script>
</body>

</html>
