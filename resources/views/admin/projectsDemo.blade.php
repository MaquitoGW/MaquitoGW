@extends('layouts.admin')
@section('title', 'Configurações de demo')
@section('content')

    <div class="options-list">
        <a href="{{ route('projects') }}">Voltar</a>
    </div>

    <form action="update/{{$project->demo}}" id="form" method="post">
        @csrf
        <label for="pagename">Nome do projeto</label>
        <input type="text" disabled value="{{$project->name}}">

        <label for="demo_location">Selecione o caminho da sua demo</label>
        
        <div class="options">
            <input type="text" name="demo_location" value="{{$project->demo_location}}" placeholder="Insirar o caminho do índice">
            <button type="submit">Salvar</button>
        </div>

        <div class="data-form">
            <label for="filemanager">Gerenciador de arquivos</label>
            <iframe width="100%" src="{{route('filemanager')}}" frameborder="0"></iframe>
        </div>
    </form>
    

    <link rel="stylesheet" href="/css/formtext.css">
    <script src="/js/construct.js"></script>
    <script>
        _construct('#textbox');
    </script>
@endsection
