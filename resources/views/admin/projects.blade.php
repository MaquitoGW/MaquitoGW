@extends('layouts.admin')
@section('title', 'Projetos')
@section('content')

    <div class="options-list">
        <a href="{{route('projects.new')}}">Novo projeto</a>
        <a href="">Ativos</a>
        <a href="">Rascunhos</a>
    </div>

@endsection
