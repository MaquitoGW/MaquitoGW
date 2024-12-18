@extends('layouts.admin')
@section('title', 'Novo projeto')
@section('content')

    <div class="options-list">
        <a href="{{ route('projects') }}">Voltar</a>
    </div>

    <form action="{{ route('projects.add') }}" id="form" method="post" enctype="multipart/form-data">
        @csrf
        <label for="pagename">Nome do projeto</label>
        <input type="text" name="name" required placeholder="Qual nome do seu projeto?">

        <label for="preview">Descrição curta</label>
        <textarea required name="preview" cols="30" rows="5" placeholder="Insirar uma breve descrição sobre o projeto."></textarea>

        <label for="description">Descrição do projeto</label>
        <div id="textbox"></div>
        <input type="text" name="description" id="description" hidden>

        <label for="skills">Habilidades usadas</label>
        <ul class="list-checkbox">
            @foreach ($skills as $skill)
                @foreach ($skillsJson as $type)
                    @foreach ($type as $code => $language)
                        @if ($code == $skill->code)
                            <li>
                                <label>
                                    <input type="checkbox" name="skills[]" value="{{ $code }}">
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
            <a href="">Salvar rascunho</a>
            <a class="success" href="">Fechar</a>
        </div>
    </form>

    <link rel="stylesheet" href="/css/formtext.css">
    <script src="/js/construct.js"></script>
    <script>
        _construct('#textbox');
    </script>
@endsection
