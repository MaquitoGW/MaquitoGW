@extends('layouts.admin')
@section('title', 'Editar projeto')
@section('content')
    @php
        $projectLanguageSettings = $projectLanguageSettings ?? ['multiple' => false, 'auto' => false, 'show' => false];
        $hasEnglishProjectData = $project->name_en || $project->preview_en || $project->description_en;
        $autoProjectTranslation = (bool) ($projectLanguageSettings['auto'] ?? false);
    @endphp

    <div class="admin-page">
        <div class="admin-header">
            <div>
                <span class="admin-eyebrow">Projeto</span>
                <h1 class="admin-title">Editar projeto</h1>
                <p class="admin-subtitle">Atualize conteúdo, mídia e arquivos da demo.</p>
            </div>
            <a href="{{ route('projects') }}" class="admin-btn">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
            <a href="{{ route('projects.files', $project->demo) }}" class="admin-btn">
                <i class="fa-solid fa-folder-open"></i> Arquivos
            </a>
        </div>

    <form action="../update/{{ $project->demo }}" id="form" method="post" enctype="multipart/form-data">
        @csrf

        <label for="pagename">Nome do projeto</label>
        <input type="text" value="{{ $project->name }}" name="name" required placeholder="Qual nome do seu projeto?">

        <label for="preview">Descrição curta</label>
        <textarea required name="preview" cols="30" rows="5"
            placeholder="Insirar uma breve descrição sobre o projeto.">{{ $project->preview }}</textarea>

        <label for="description">Descrição do projeto</label>
        <div id="textbox">{{ $project->description }}</div>
        <input type="text" name="description" id="description" hidden>

        @if ($projectLanguageSettings['show'])
        <div class="admin-card bilingual-project-card" data-manual="{{ $projectLanguageSettings['multiple'] ? '1' : '0' }}">
            <h3>Idioma do projeto</h3>
            @if (!$projectLanguageSettings['multiple'] && $projectLanguageSettings['auto'])
                <input type="hidden" name="project_bilingual" value="1">
                <input type="hidden" name="auto_translate" value="1">
            @endif
            <label class="admin-check">
                <input type="checkbox" name="project_bilingual" value="1" @checked($hasEnglishProjectData || $autoProjectTranslation) @disabled(!$projectLanguageSettings['multiple'] && $projectLanguageSettings['auto'])>
                <span>Adicionar versao em ingles</span>
            </label>
            @if ($projectLanguageSettings['auto'])
            <label class="admin-check">
                <input type="checkbox" name="auto_translate" value="1" @checked($autoProjectTranslation) @disabled(!$projectLanguageSettings['multiple'])>
                <span>Traduzir campos vazios automaticamente usando as configuracoes do servidor</span>
            </label>
            @endif
            <span class="info">
                @if ($projectLanguageSettings['auto'])
                    Com a traducao automatica ativa, os campos em ingles ficam bloqueados e serao atualizados ao salvar.
                @else
                    Edite os campos em ingles manualmente para manter o projeto em dois idiomas.
                @endif
            </span>

            <label for="name_en">Nome do projeto em ingles</label>
            <input type="text" name="name_en" value="{{ $project->name_en }}" placeholder="Project name in English" @disabled($autoProjectTranslation)>

            <label for="preview_en">Descricao curta em ingles</label>
            <textarea name="preview_en" cols="30" rows="4" placeholder="Short project description in English" @disabled($autoProjectTranslation)>{{ $project->preview_en }}</textarea>

            <label for="description_en">Descricao do projeto em ingles</label>
            <textarea name="description_en" cols="30" rows="6" placeholder="HTML is supported" @disabled($autoProjectTranslation)>{{ $project->description_en }}</textarea>
        </div>
        @endif

        <label for="skills">Habilidades usadas</label>
        <ul class="list-checkbox">
            @foreach ($skills as $skill)
                @foreach ($skillsJson as $type)
                    @foreach ($type as $code => $language)
                        @if ($code == $skill->code)
                            <li>
                                <label>
                                    <input
                                        @foreach ($skillsChecked as $item) @if ($code == $item) checked @endif @endforeach
                                        type="checkbox" name="skills[]" value="{{ $code }}">
                                    <i class="{{ $language['icon'] }}"></i>
                                    {{ $language['name'] }}
                                </label>
                            </li>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
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

                @foreach ($images as $image)
                    <div class="added_data_wrapper">
                        <img src="{{ $image }}" class="added_data" alt="Imagem">
                        <input class="inputImage" type="checkbox" value="{{ $image }}" checked hidden name="images[]">
                    </div>
                @endforeach

            </div>
        </div>

        <label for="video">Vídeo</label>
        <div class="video-preview">
            <video src="/{{ $project->videos }}" controls></video>
            <span class="info">Selecione um vídeo do seu projeto</span>
        </div>

        <span class="icon">
            <i class="fa-light fa-video"></i>
            <input type="file" name="videos" accept="video/mp4">
        </span>

        <label for="github">Link do seu projeto no GitHub</label>
        <span class="icon">
            <i class="fa-brands fa-github"></i>
            <input required type="url" name="github" value="{{ $project->github }}"
                placeholder="Insirar aqui o link do projeto">
        </span>

        <div class="options">
            <button type="submit">Salvar alterações</button>
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
