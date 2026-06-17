@extends('layouts.admin')
@section('title', 'Configuracoes')

@section('content')
    @php
        $settingsUser = Auth::user();
        $serverSettings = $serverSettings ?? [];
        $settingsInitials = collect(explode(' ', trim($settingsUser->name ?? 'User')))
            ->filter()
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    @endphp

    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Sistema</span>
                <h1 class="admin-title">Configuracoes</h1>
                <p class="admin-subtitle">Atualize dados da conta, seguranca e preferencias do servidor.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="admin-alert admin-alert-error">
                <strong>Revise os campos abaixo.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="settings-grid">
            <form method="POST" action="{{ route('update.auth') }}" class="admin-card admin-form">
                @csrf
                <div>
                    <span class="admin-eyebrow">Conta</span>
                    <h3>Dados de acesso</h3>
                    <p class="admin-subtitle">Essas informacoes aparecem no header do painel.</p>
                </div>

                <div class="account-summary">
                    <div class="admin-avatar" aria-hidden="true">{{ $settingsInitials }}</div>
                    <div>
                        <strong>{{ $settingsUser->name }}</strong>
                        <span>{{ $settingsUser->email }}</span>
                    </div>
                </div>

                <div class="form-grid-admin">
                    <div class="admin-field">
                        <label for="name">Nome</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $settingsUser->name) }}" required>
                    </div>

                    <div class="admin-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $settingsUser->email) }}" required>
                    </div>

                    <div class="admin-field">
                        <label for="password">Nova senha</label>
                        <input type="password" id="password" name="password" placeholder="Preencha apenas para alterar">
                    </div>

                    <div class="admin-field">
                        <label for="password_confirmation">Confirmar senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repita a nova senha">
                    </div>
                </div>

                <div class="admin-form-actions">
                    <button type="submit">Atualizar conta</button>
                </div>
            </form>

            <form method="POST" action="{{ route('settings.update') }}" class="admin-card admin-form">
                @csrf
                <div>
                    <span class="admin-eyebrow">Servidor</span>
                    <h3>Preferencias da aplicacao</h3>
                    <p class="admin-subtitle">Configuracoes salvas no arquivo .env do projeto.</p>
                </div>

                <div class="form-grid-admin">
                    <div class="admin-field">
                        <label for="app-name">Nome do servidor</label>
                        <input type="text" id="app-name" name="app-name" value="{{ old('app-name', $serverSettings['APP_TITLE'] ?? env('APP_TITLE')) }}">
                    </div>

                    <div class="admin-field">
                        <label for="app-env">Ambiente</label>
                        <select id="app-env" name="app-env">
                            <option value="local" {{ old('app-env', $serverSettings['APP_ENV'] ?? env('APP_ENV')) == 'local' ? 'selected' : '' }}>Local</option>
                            <option value="production" {{ old('app-env', $serverSettings['APP_ENV'] ?? env('APP_ENV')) == 'production' ? 'selected' : '' }}>Producao</option>
                            <option value="staging" {{ old('app-env', $serverSettings['APP_ENV'] ?? env('APP_ENV')) == 'staging' ? 'selected' : '' }}>Staging</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="app-debug">Depuracao</label>
                        <select id="app-debug" name="app-debug">
                            <option value="true" {{ old('app-debug', $serverSettings['APP_DEBUG'] ?? env('APP_DEBUG')) == 'true' ? 'selected' : '' }}>Ativado</option>
                            <option value="false" {{ old('app-debug', $serverSettings['APP_DEBUG'] ?? env('APP_DEBUG')) == 'false' ? 'selected' : '' }}>Desativado</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="app-timezone">Fuso horario</label>
                        <select id="app-timezone" name="app-timezone">
                            @php $currentTimezone = old('app-timezone', $serverSettings['APP_TIMEZONE'] ?? env('APP_TIMEZONE')); @endphp
                            <option value="UTC" {{ $currentTimezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/Sao_Paulo" {{ $currentTimezone == 'America/Sao_Paulo' ? 'selected' : '' }}>Brasilia (BRT)</option>
                            <option value="America/Fortaleza" {{ $currentTimezone == 'America/Fortaleza' ? 'selected' : '' }}>Fortaleza (BRT)</option>
                            <option value="America/Manaus" {{ $currentTimezone == 'America/Manaus' ? 'selected' : '' }}>Manaus (AMT)</option>
                            <option value="America/Cuiaba" {{ $currentTimezone == 'America/Cuiaba' ? 'selected' : '' }}>Cuiaba (AMT)</option>
                            <option value="America/Porto_Velho" {{ $currentTimezone == 'America/Porto_Velho' ? 'selected' : '' }}>Porto Velho (AMT)</option>
                            <option value="America/New_York" {{ $currentTimezone == 'America/New_York' ? 'selected' : '' }}>New York (EST)</option>
                            <option value="America/Toronto" {{ $currentTimezone == 'America/Toronto' ? 'selected' : '' }}>Toronto (EST)</option>
                            <option value="America/Mexico_City" {{ $currentTimezone == 'America/Mexico_City' ? 'selected' : '' }}>Mexico City (CST)</option>
                            <option value="America/Buenos_Aires" {{ $currentTimezone == 'America/Buenos_Aires' ? 'selected' : '' }}>Buenos Aires (ART)</option>
                            <option value="America/Lima" {{ $currentTimezone == 'America/Lima' ? 'selected' : '' }}>Lima (PET)</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="app-faker-locale">Idioma principal</label>
                        <select id="app-faker-locale" name="app-faker-locale">
                            <option value="en_US" {{ old('app-faker-locale', $serverSettings['APP_FAKER_LOCALE'] ?? env('APP_FAKER_LOCALE')) == 'en_US' ? 'selected' : '' }}>Ingles (EUA)</option>
                            <option value="pt_BR" {{ old('app-faker-locale', $serverSettings['APP_FAKER_LOCALE'] ?? env('APP_FAKER_LOCALE')) == 'pt_BR' ? 'selected' : '' }}>Portugues (Brasil)</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="multiple-languages">Multiplos idiomas</label>
                        <select id="multiple-languages" name="multiple-languages">
                            <option value="1" {{ old('multiple-languages', $serverSettings['MULTIPLE_LANGUAGES'] ?? env('MULTIPLE_LANGUAGES')) == 1 ? 'selected' : '' }}>Ativado</option>
                            <option value="0" {{ old('multiple-languages', $serverSettings['MULTIPLE_LANGUAGES'] ?? env('MULTIPLE_LANGUAGES')) == 0 ? 'selected' : '' }}>Desativado</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="project-translation-enabled">Traducao automatica de projetos</label>
                        <select id="project-translation-enabled" name="project-translation-enabled">
                            <option value="true" {{ old('project-translation-enabled', $serverSettings['PROJECT_TRANSLATION_ENABLED'] ?? env('PROJECT_TRANSLATION_ENABLED')) == 'true' ? 'selected' : '' }}>Ativado</option>
                            <option value="false" {{ old('project-translation-enabled', $serverSettings['PROJECT_TRANSLATION_ENABLED'] ?? env('PROJECT_TRANSLATION_ENABLED', 'false')) == 'false' ? 'selected' : '' }}>Desativado</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="project-translation-provider">API de traducao</label>
                        <select id="project-translation-provider" name="project-translation-provider">
                            <option value="google" {{ old('project-translation-provider', $serverSettings['PROJECT_TRANSLATION_PROVIDER'] ?? env('PROJECT_TRANSLATION_PROVIDER', 'google')) == 'google' ? 'selected' : '' }}>Google Translate</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="google-translate-url">URL da API Google Translate</label>
                        <input type="url" id="google-translate-url" name="google-translate-url" value="{{ old('google-translate-url', $serverSettings['GOOGLE_TRANSLATE_URL'] ?? env('GOOGLE_TRANSLATE_URL', 'https://translate.googleapis.com/translate_a/single')) }}" placeholder="https://translate.googleapis.com/translate_a/single">
                    </div>

                    <div class="admin-field">
                        <label for="portfolio-enabled">Portfolio publico</label>
                        <select id="portfolio-enabled" name="portfolio-enabled">
                            <option value="true" {{ old('portfolio-enabled', $serverSettings['PORTFOLIO_ENABLED'] ?? env('PORTFOLIO_ENABLED', 'true')) == 'true' ? 'selected' : '' }}>Ativado</option>
                            <option value="false" {{ old('portfolio-enabled', $serverSettings['PORTFOLIO_ENABLED'] ?? env('PORTFOLIO_ENABLED', 'true')) == 'false' ? 'selected' : '' }}>Desativado</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="file-storage-driver">Storage de arquivos</label>
                        <select id="file-storage-driver" name="file-storage-driver">
                            <option value="local" {{ old('file-storage-driver', $serverSettings['FILE_STORAGE_DRIVER'] ?? env('FILE_STORAGE_DRIVER', 'local')) == 'local' ? 'selected' : '' }}>Local</option>
                            <option value="r2" {{ old('file-storage-driver', $serverSettings['FILE_STORAGE_DRIVER'] ?? env('FILE_STORAGE_DRIVER', 'local')) == 'r2' ? 'selected' : '' }}>Cloudflare R2</option>
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="r2-endpoint">R2 Endpoint</label>
                        <input type="url" id="r2-endpoint" name="r2-endpoint" value="{{ old('r2-endpoint', $serverSettings['R2_ENDPOINT'] ?? env('R2_ENDPOINT')) }}" placeholder="https://accountid.r2.cloudflarestorage.com">
                    </div>

                    <div class="admin-field">
                        <label for="r2-public-url">R2 URL publica</label>
                        <input type="url" id="r2-public-url" name="r2-public-url" value="{{ old('r2-public-url', $serverSettings['R2_PUBLIC_URL'] ?? env('R2_PUBLIC_URL')) }}" placeholder="https://cdn.seudominio.com">
                    </div>

                    <div class="admin-field">
                        <label for="r2-bucket">R2 Bucket</label>
                        <input type="text" id="r2-bucket" name="r2-bucket" value="{{ old('r2-bucket', $serverSettings['R2_BUCKET'] ?? env('R2_BUCKET')) }}">
                    </div>

                    <div class="admin-field">
                        <label for="r2-default-region">R2 Regiao</label>
                        <input type="text" id="r2-default-region" name="r2-default-region" value="{{ old('r2-default-region', $serverSettings['R2_DEFAULT_REGION'] ?? env('R2_DEFAULT_REGION', 'auto')) }}">
                    </div>

                    <div class="admin-field">
                        <label for="r2-access-key-id">R2 Access Key</label>
                        <input type="text" id="r2-access-key-id" name="r2-access-key-id" value="{{ old('r2-access-key-id', $serverSettings['R2_ACCESS_KEY_ID'] ?? env('R2_ACCESS_KEY_ID')) }}">
                    </div>

                    <div class="admin-field">
                        <label for="r2-secret-access-key">R2 Secret Key</label>
                        <input type="password" id="r2-secret-access-key" name="r2-secret-access-key" value="{{ old('r2-secret-access-key', $serverSettings['R2_SECRET_ACCESS_KEY'] ?? env('R2_SECRET_ACCESS_KEY')) }}">
                    </div>
                </div>

                <div class="admin-form-actions">
                    <button type="submit">Atualizar servidor</button>
                </div>
            </form>
        </div>
    </div>
@endsection
