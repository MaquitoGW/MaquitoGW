@extends('layouts.site')

@section('title', !empty($portfolioDisabled) ? 'Portfolio indisponivel' : 'Site em configuracao')

@section('main')
    @php
        $isDisabled = !empty($portfolioDisabled);
        $statusItems = [
            [
                'label' => 'Perfil profissional',
                'state' => isset($infos) ? 'ok' : ($isDisabled ? 'neutral' : 'error'),
                'text' => isset($infos) ? 'Configurado' : 'Aguardando dados',
            ],
            [
                'label' => 'Contatos',
                'state' => isset($contacts) ? 'ok' : ($isDisabled ? 'neutral' : 'error'),
                'text' => isset($contacts) ? 'Configurado' : 'Aguardando dados',
            ],
            [
                'label' => 'Projetos',
                'state' => isset($projects) ? 'ok' : 'neutral',
                'text' => isset($projects) ? 'Disponiveis' : 'Opcional',
            ],
        ];
    @endphp

    <section class="setup-shell">
        <div class="setup-panel">
            <div class="setup-header">
                <span class="setup-badge">
                    <i class="fa-solid {{ $isDisabled ? 'fa-power-off' : 'fa-screwdriver-wrench' }}"></i>
                    {{ $isDisabled ? 'Portfolio desativado' : 'Configuracao pendente' }}
                </span>

                <h1>{{ $isDisabled ? 'Portfolio temporariamente indisponivel' : 'Site em configuracao' }}</h1>

                <p>
                    @if ($isDisabled)
                        O portfolio publico foi desativado nas configuracoes do painel. A area administrativa continua disponivel para manutencao, ajustes de conteudo e reativacao.
                    @else
                        Alguns dados essenciais ainda nao foram configurados. Complete as informacoes no painel para publicar o portfolio com seguranca.
                    @endif
                </p>
            </div>

            <div class="setup-status-grid">
                @foreach ($statusItems as $item)
                    <div class="setup-status-card">
                        <span class="setup-status-dot {{ $item['state'] }}"></span>
                        <div>
                            <strong>{{ $item['label'] }}</strong>
                            <small>{{ $item['text'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="setup-actions">
                <a href="{{ route('login') }}" class="setup-btn setup-btn-primary">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    Acessar painel
                </a>
                <a href="/" class="setup-btn setup-btn-secondary">
                    <i class="fa-solid fa-rotate-right"></i>
                    Verificar novamente
                </a>
            </div>

            <div class="setup-footer-note">
                <i class="fa-regular fa-circle-check"></i>
                Sistema online em modo seguro de fallback.
            </div>
        </div>
    </section>

    <style>
        :root,
        body[data-app-theme-effective="light"] {
            --setup-bg: #f6f8fa;
            --setup-panel: #ffffff;
            --setup-border: #d0d7de;
            --setup-text: #24292f;
            --setup-muted: #57606a;
            --setup-subtle: #f6f8fa;
            --setup-primary: var(--primary-color, #2f81f7);
            --setup-shadow: 0 24px 60px rgba(31, 35, 40, 0.12);
        }

        html.dark,
        .dark,
        body[data-app-theme-effective="dark"] {
            --setup-bg: #0d1117;
            --setup-panel: #161b22;
            --setup-border: #30363d;
            --setup-text: #e6edf3;
            --setup-muted: #8b949e;
            --setup-subtle: #0d1117;
            --setup-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-app-theme-effective="light"]) {
                --setup-bg: #0d1117;
                --setup-panel: #161b22;
                --setup-border: #30363d;
                --setup-text: #e6edf3;
                --setup-muted: #8b949e;
                --setup-subtle: #0d1117;
                --setup-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            }
        }

        body {
            background: var(--setup-bg) !important;
            overflow-x: hidden;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
        }

        body > main.container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .setup-shell {
            position: fixed;
            inset: 0;
            width: 100%;
            min-height: 100dvh;
            margin: 0 !important;
            overflow-y: auto;
            display: grid;
            place-items: center;
            padding: 48px 20px;
            box-sizing: border-box;
            background:
                radial-gradient(circle at 0 0, rgba(47, 129, 247, 0.14) 0, rgba(47, 129, 247, 0.08) 14rem, transparent 30rem),
                var(--setup-bg);
            color: var(--setup-text);
        }

        .setup-panel {
            width: min(760px, 100%);
            border: 1px solid var(--setup-border);
            border-radius: 16px;
            background: var(--setup-panel);
            box-shadow: var(--setup-shadow);
            padding: 32px;
        }

        .setup-header {
            max-width: 620px;
        }

        .setup-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border: 1px solid var(--setup-border);
            border-radius: 999px;
            background: var(--setup-subtle);
            color: var(--setup-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .setup-header h1 {
            margin: 18px 0 10px;
            color: var(--setup-text);
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.08;
            letter-spacing: 0;
        }

        .setup-header p {
            margin: 0;
            color: var(--setup-muted);
            font-size: 16px;
            line-height: 1.65;
        }

        .setup-status-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin: 28px 0;
        }

        .setup-status-card {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 74px;
            padding: 14px;
            border: 1px solid var(--setup-border);
            border-radius: 12px;
            background: var(--setup-subtle);
        }

        .setup-status-card strong,
        .setup-status-card small {
            display: block;
        }

        .setup-status-card strong {
            color: var(--setup-text);
            font-size: 14px;
        }

        .setup-status-card small {
            margin-top: 3px;
            color: var(--setup-muted);
            font-size: 12px;
        }

        .setup-status-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #6e7681;
            box-shadow: 0 0 0 4px rgba(110, 118, 129, 0.12);
        }

        .setup-status-dot.ok {
            background: #2da44e;
            box-shadow: 0 0 0 4px rgba(45, 164, 78, 0.14);
        }

        .setup-status-dot.error {
            background: #cf222e;
            box-shadow: 0 0 0 4px rgba(207, 34, 46, 0.14);
        }

        .setup-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .setup-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 10px;
            border: 1px solid var(--setup-border);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        }

        .setup-btn:hover {
            transform: translateY(-1px);
        }

        .setup-btn-primary {
            border-color: var(--setup-primary);
            background: var(--setup-primary);
            color: #ffffff;
        }

        .setup-btn-secondary {
            background: var(--setup-panel);
            color: var(--setup-text);
        }

        .setup-btn-secondary:hover {
            background: var(--setup-subtle);
        }

        .setup-footer-note {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--setup-border);
            color: var(--setup-muted);
            font-size: 13px;
        }

        .setup-footer-note i {
            color: #2da44e;
        }

        @media (max-width: 720px) {
            .setup-panel {
                padding: 24px;
                border-radius: 14px;
            }

            .setup-status-grid {
                grid-template-columns: 1fr;
            }

            .setup-btn {
                width: 100%;
            }
        }
    </style>
@endsection
