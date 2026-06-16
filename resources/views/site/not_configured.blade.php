@extends('layouts.site')

@section('title', 'Site em Configuração')

@section('main')

    <section class="setup-container">

        <div class="setup-card">

            <div class="setup-icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>

            <h1 class="setup-title">
                Site em configuração
            </h1>

            <p class="setup-description">
                O portfólio ainda não foi totalmente configurado ou está em processo de inicialização.
                Alguns dados essenciais não foram encontrados no sistema.
            </p>

            <div class="setup-status">

                <div class="status-item">
                    <span class="dot {{ isset($infos) ? 'ok' : 'error' }}"></span>
                    <p>Informações do perfil</p>
                </div>

                <div class="status-item">
                    <span class="dot {{ isset($contacts) ? 'ok' : 'error' }}"></span>
                    <p>Contatos</p>
                </div>

                <div class="status-item">
                    <span class="dot {{ isset($projects) ? 'ok' : 'warn' }}"></span>
                    <p>Projetos</p>
                </div>

            </div>

            <div class="setup-actions">

                <a href="/" class="btn-primary">
                    <i class="fa-solid fa-rotate"></i>
                    Recarregar
                </a>

                <a href="{{ route('login') }}" class="btn-secondary">
                    <i class="fa-solid fa-lock"></i>
                    Acessar painel
                </a>

            </div>

            <p class="setup-footer">
                Sistema carregado em modo seguro de fallback
            </p>

        </div>

    </section>

    <style>
        .setup-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .setup-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .setup-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: var(--primary-color, #4f46e5);
        }

        .setup-title {
            font-size: 24px;
            margin-bottom: 10px;
            font-family: 'Aldrich', sans-serif;
        }

        .setup-description {
            font-size: 14px;
            opacity: 0.7;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .setup-status {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 25px;
        }

        .status-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 13px;
            opacity: 0.8;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot.ok {
            background: #22c55e;
        }

        .dot.error {
            background: #ef4444;
        }

        .dot.warn {
            background: #f59e0b;
        }

        .setup-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-secondary {
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: inherit;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .setup-footer {
            font-size: 11px;
            opacity: 0.5;
        }
    </style>

@endsection
