@extends('layouts.admin')
@section('title', 'Novo projeto')
@section('content')
    @php
        $projectLanguageSettings = $projectLanguageSettings ?? ['multiple' => false, 'auto' => false, 'show' => false];
        $autoProjectTranslation = (bool) ($projectLanguageSettings['auto'] ?? false);
    @endphp

    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Projeto</span>
                <h1 class="admin-title">Novo projeto</h1>
                <p class="admin-subtitle">Cadastre descrição, mídia, habilidades e pacote da demo.</p>
            </div>
            <a href="{{ route('projects') }}" class="admin-btn">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

    <form action="{{ route('projects.add') }}" id="form" method="post" enctype="multipart/form-data">
        @csrf
        <label for="pagename">Nome do projeto</label>
        <input type="text" name="name" required placeholder="Qual nome do seu projeto?">

        <label for="preview">Descrição curta</label>
        <textarea required name="preview" cols="30" rows="5"
            placeholder="Insirar uma breve descrição sobre o projeto."></textarea>

        <label for="description">Descrição do projeto</label>
        <div id="textbox"></div>
        <input type="text" name="description" id="description" hidden>

        @if ($projectLanguageSettings['show'])
        <div class="admin-card bilingual-project-card" data-manual="{{ $projectLanguageSettings['multiple'] ? '1' : '0' }}">
            <h3>Idioma do projeto</h3>
            @if (!$projectLanguageSettings['multiple'] && $projectLanguageSettings['auto'])
                <input type="hidden" name="project_bilingual" value="1">
                <input type="hidden" name="auto_translate" value="1">
            @endif
            <label class="admin-check">
                <input type="checkbox" name="project_bilingual" value="1" @checked($autoProjectTranslation) @disabled(!$projectLanguageSettings['multiple'] && $projectLanguageSettings['auto'])>
                <span>Adicionar versao em ingles</span>
            </label>
            @if ($projectLanguageSettings['auto'])
            <label class="admin-check">
                <input type="checkbox" name="auto_translate" value="1" @checked($autoProjectTranslation) @disabled(!$projectLanguageSettings['multiple'])>
                <span>Traduzir automaticamente usando as configuracoes do servidor</span>
            </label>
            @endif
            <span class="info">
                @if ($projectLanguageSettings['auto'])
                    Com a traducao automatica ativa, os campos em ingles ficam bloqueados e serao gerados ao salvar.
                @else
                    Preencha os campos em ingles manualmente para publicar o projeto em dois idiomas.
                @endif
            </span>

            <label for="name_en">Nome do projeto em ingles</label>
            <input type="text" name="name_en" placeholder="Project name in English" @disabled($autoProjectTranslation)>

            <label for="preview_en">Descricao curta em ingles</label>
            <textarea name="preview_en" cols="30" rows="4" placeholder="Short project description in English" @disabled($autoProjectTranslation)></textarea>

            <label for="description_en">Descricao do projeto em ingles</label>
            <textarea name="description_en" cols="30" rows="6" placeholder="HTML is supported" @disabled($autoProjectTranslation)></textarea>
        </div>
        @endif

        <label for="skills">Habilidades usadas</label>
        <ul class="list-checkbox">
            @if (count($skills) > 0)
                @foreach ($skills as $skill)
                    @foreach ($skillsJson as $type)
                        @foreach ($type as $code => $language)
                            @if ($code == $skill->code)
                                <li>
                                    <label class="li-skills">
                                        <input type="checkbox" name="skills[]" value="{{ $code }}">
                                        <i class="{{ $language['icon'] }}"></i>
                                        {{ $language['name'] }}
                                    </label>
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @else
                <p class="alert">
                    <span class="icon"><i class="fa-light fa-circle-exclamation"></i></span>
                    <span>Adicione pelo menos uma habilidade.</span>
                </p>
            @endif
        </ul>

        <div class="data-form">
            <label for="images">Imagens</label>
            <div class="data" id="added">
                <div class="new_data" id="new_image">
                    <span class="icon-form"><i class="fa-light fa-image"></i></span>
                    <span class="text">Nova imagem</span>
                    <span class="info">Selecione uma imagem 1920x1080</span>
                    <input type="file" id="fileInput" hidden multiple accept="image/*">
                </div>

            </div>
        </div>

        <label for="video">Vídeo</label>
        <span class="info">Selecione um vídeo do seu projeto</span>
        <span class="icon">
            <i class="fa-light fa-video"></i>
            <input type="file" name="videos" accept="video/mp4">
        </span>

        <label for="github">Link do seu projeto no GitHub</label>
        <span class="icon">
            <i class="fa-brands fa-github"></i>
            <input required type="url" name="github" placeholder="Insirar aqui o link do projeto">
        </span>

        <label for="project">Seu projeto</label>
        <span class="info">Envie seu projeto compactado</span>
        <input type="file" required name="project" accept=".zip,.rar,.7z,.tar.gz,.tar">

        <div class="options">
            <button type="submit">Adicionar</button>
            <a class="success" href="{{ route('projects') }}">Fechar</a>
        </div>
    </form>

    <link rel="stylesheet" href="/css/formtext.css">
    <script src="/js/construct.js"></script>
    <script>
        _construct('#textbox');

        document.querySelectorAll('.bilingual-project-card').forEach((card) => {
            const manualAllowed = card.dataset.manual === '1';
            const bilingual = card.querySelector('[name="project_bilingual"]');
            const automatic = card.querySelector('[name="auto_translate"]');
            const fields = card.querySelectorAll('[name="name_en"], [name="preview_en"], [name="description_en"]');

            const refreshProjectLanguageFields = () => {
                const isAutomatic = automatic?.checked;
                const isBilingual = bilingual?.checked || isAutomatic;

                if (isAutomatic && bilingual) {
                    bilingual.checked = true;
                }

                fields.forEach((field) => {
                    field.disabled = isAutomatic || !isBilingual || !manualAllowed;
                });
            };

            bilingual?.addEventListener('change', refreshProjectLanguageFields);
            automatic?.addEventListener('change', refreshProjectLanguageFields);
            refreshProjectLanguageFields();
        });
    </script>
    </div>
@endsection
