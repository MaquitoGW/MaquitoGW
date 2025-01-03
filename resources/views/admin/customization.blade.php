@extends('layouts.admin')
@section('title', 'Personalização')
@section('content')
    <form action="{{route('customization.update')}}" method="post">
        @csrf
        <h3>Personalizações adicionais</h3>
            <label for="color_primary">Cor principal</label>
            <input type="color" name="color_primary" value="{{ $search('color_primary', '#6200ff') }}">

            <label for="color_secondary">Cor secundaria</label>
            <input type="color" name="color_secondary" value="{{ $search('color_secondary', '#8400ff') }}">
        <button type="submit">Atualizar</button>
    </form>

    <form action="{{route('customization.update.images')}}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Imagens do usuário</h3>
        <label for="myphoto">Sua foto</label>
        <img class="images" src="{{ $search('myphoto', '/img/my.jpg') }}" alt="myphoto">
        <span class="info">Selecione uma imagem 1800x2500</span>
        <input type="file" name="myphoto" accept="image/*">

        <label for="bklogo">Imagem inicial</label>
        <img class="images" src="{{ $search('bklogo', '/img/bk_logo.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 1000x1000</span>
        <input type="file" name="bklogo" accept="image/*">

        <button type="submit">Atualizar</button>
    </form>

    <form action="{{route('customization.update.images')}}" method="post" enctype="multipart/form-data">
        @csrf
        <h3>Logos</h3>
        <label for="favicon">Favicon</label>
        <img class="images" src="{{ $search('favicon', '/img/favicon.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 500x500</span>
        <input type="file" name="favicon" accept="image/*">

        <label for="logotipo">Logotipo Branca</label>
        <img class="images" src="{{ $search('logotipo', '/img/logo.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 2560x500</span>
        <input type="file" name="logotipo" accept="image/*">

        <label for="logotipo_color">Logotipo Colorida</label>
        <img class="images none" src="{{ $search('logotipo_color', '/img/logo_color.png') }}" alt="Favicon">
        <span class="info">Selecione uma imagem 2560x500</span>
        <input type="file" name="logotipo_color" accept="image/*">

        <button type="submit">Atualizar</button>
    </form>
@endsection
