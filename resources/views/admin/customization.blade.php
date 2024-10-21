@extends('layouts.admin')
@section('title', 'Personalização')
@section('content')
    <form action="" method="post">
        @csrf
        <h3>Personalizações adicionais</h3>
        <label for="pagename">Nome da página</label>
        <input type="text" name="pagename" placeholder="Insirar seu apelido">

        <label for="color_primary">Cor principal</label>
        <input type="color" name="color_primary">

        <label for="thema">Selecione um tema</label>
        <select name="thema">
            <option disabled selected>Selecione um tema</option>
            <option value="light_mode">Light mode</option>
            <option value="dark_mode">Dark mode</option>
        </select>


        <button type="submit">Atualizar</button>
    </form>

    <form action="" method="post">
        @csrf
        <h3>Logos</h3>
        <label for="favicon">Favicon</label>
        <img class="images" src="/img/favicon.png" alt="Favicon">
        <span class="info">Selecione uma imagem 500x500</span>
        <input type="file" name="favicon" accept="image/*">

        <label for="logotipo">Logotipo</label>
        <img class="images" src="/img/logo.png" alt="Favicon">
        <span class="info">Selecione uma imagem 2560x500</span>
        <input type="file" name="logotipo" accept="image/*">

        <button type="submit">Atualizar</button>
    </form>
@endsection
