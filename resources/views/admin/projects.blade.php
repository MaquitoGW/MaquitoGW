@extends('layouts.admin')
@section('title', 'Projetos')
@section('content')

    <div class="options-list">
        <a href="">Novo projeto</a>
        <a href="">Ativos</a>
        <a href="">Rascunhos</a>
    </div>

    {{-- Formulario --}}
    <form action="" id="form" method="post">
        <label for="pagename">Nome do projeto</label>
        <input type="text" name="name" value="" placeholder="Qual nome do seu projeto?">

        <label for="description">Descrição do projeto</label>
        <div id="textbox"></div>

        <label for="skills">Habilidades usadas</label>
        <ul class="list-checkbox">
            <li>
                <label>
                    <input type="checkbox" name="skills" value="Python"> Python
                </label>
            </li>
        </ul>

        <div class="data-form">
            <label for="images">Imagens</label>
            <div class="data" id="added">
                <div class="new_data" id="new_image">
                    <span class="icon-form"><i class="fa-light fa-image"></i></span>
                    <span class="text">Nova imagem</span>
                    <span class="info">Selecione uma imagem 1920x1080</span>
                    <input type="file" id="fileInput" hidden multiple>                    
                </div>
            </div>
        </div>

        <label for="video">Vídeo</label>
        <span class="info">Selecione um vídeo do seu projeto</span>
        <span class="icon">
            <i class="fa-light fa-video"></i>
            <input type="file" name="video" accept="video/mp4">
        </span>

        <label for="github">Link do seu projeto no GitHub</label>
        <span class="icon">
            <i class="fa-brands fa-github"></i>
            <input required type="url" name="github" placeholder="Insirar aqui o link do projeto">
        </span>

        <label for="project">Seu projeto</label>
        <span class="info">Envie seu projeto compactado</span>
        <input type="file" name="project" accept=".zip,.rar,.7z,.tar.gz,.tar">

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
