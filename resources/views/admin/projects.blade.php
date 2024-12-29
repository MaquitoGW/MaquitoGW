@extends('layouts.admin')
@section('title', 'Projetos')
@section('content')

    <div class="options-list">
        <a href="{{ route('projects.new') }}">Novo projeto</a>
    </div>

    <div class="list-itens">
        <h3>Meus projetos</h3>
        @foreach ($projects as $project)
            <div class="item">
                <div>
                    <span>{{ $project['name'] }}</span>
                </div>
                <p>{{ $project['preview'] }}</p>
                <ul class="list-options">
                    <li><a href="projects/delete/{{$project['demo']}}">Apagar</a></li>
                    <li><a href="projects/edit/{{$project['demo']}}">Editar</a></li>
                    <li><a href="/demos/{{$project['demo']}}">Ver Demo</a></li>
                </ul>
            </div>
        @endforeach
    </div>
@endsection
