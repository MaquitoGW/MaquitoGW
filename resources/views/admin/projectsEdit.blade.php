@extends('layouts.admin')
@section('title', 'Editar projeto')
@section('content')

    <div class="options-list">
        <a href="{{ route('projects') }}">Voltar</a>
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

        <div class="data-form">
            <label for="filemanager">Gerenciador de arquivos</label>
            <iframe width="100%" src="{{ route('filemanager') }}" frameborder="0"></iframe>
        </div>

        <div class="options">
            <button type="submit">Salvar alterações</button>
        </div>
    </form>

    <link rel="stylesheet" href="/css/formtext.css">
    <script src="/js/construct.js"></script>
    <script>
        _construct('#textbox');
    </script>
@endsection
