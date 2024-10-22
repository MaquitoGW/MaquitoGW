@extends('layouts.admin')
@section('title', 'Personalização')
@section('content')
    <form action="{{route('customization.update')}}" method="post">
        @csrf
        <h3>Personalizações adicionais</h3>
            <label for="pagename">Nome da página</label>
            <input type="text" name="pagename" value="{{ $search('pagename', 'MaquitoGW') }}" placeholder="Insirar seu apelido">

            <label for="color_primary">Cor principal</label>
            <input type="color" name="color_primary" value="{{ $search('color_primary', '#6200ff') }}">
        <button type="submit">Atualizar</button>
    </form>

    <form action="{{route('customization.update.images')}}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Logos</h3>
        <label for="favicon">Favicon</label>
        <img class="images" src="{{ $search('favicon', '/img/favicon.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 500x500</span>
        <input type="file" name="favicon" accept="image/*">

        <label for="logotipo">Logotipo</label>
        <img class="images" src="{{ $search('logotipo', '/img/logo.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 2560x500</span>
        <input type="file" name="logotipo" accept="image/*">

        <button type="submit">Atualizar</button>
    </form>
@endsection
