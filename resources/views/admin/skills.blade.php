@extends('layouts.admin')
@section('title', 'Habilidades')
@section('content')
    <div class="options-list">
        <a href="{{ route('skill_new') }}"> Adicionar Habilidades</a>
    </div>

    @if (Route::currentRouteName() == 'skill_new')
        <form action="{{route('skill_add')}}" method="post">
            @csrf
            <h3>Adicionar Habilidade</h3>
            <label for="skills">Habilidade</label>
            <select name="skill" id="skills" required>
                <option disabled selected>Selecione uma habilidade</option>
                <option value="java">Java</option>
                <option value="python">Python</option>
            </select>

            <label for="year">Ano</label>
            <input required type="number" name="year" min="2000" max="2100" placeholder="Year">

            <label for="description">Descrição</label>
            <textarea name="description" id="description" cols="30" rows="10"
                placeholder="Essa habilidade consiste em....."></textarea>

            <button type="submit">Adicionar</button>
        </form>
    @endif

    <table>
        <thead>
            <tr>
                <th>Habilidade</th>
                <th>Ano</th>
                <th>Descrição</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($skills as $item)
                <tr>
                    <td>
                        <span class="icon"><i class="fa-brands fa-{{ strtolower($item->language) }}"></i></span><br>
                        <span>{{ $item->language }}</span>
                    </td>
                    <td>{{ $item->year }}</td>
                    <td>{{ $item->info }}</td>
                    <td>
                        <a href="">Remover</a>
                        <a href="">Editar</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
