@extends('layouts.admin')
@section('title', 'Habilidades')
@section('content')
    <div class="options-list">
        <a href="{{ route('skill_new') }}"> Adicionar Habilidades</a>
    </div>

    @if (Route::currentRouteName() == 'skill_new')
        <form action="{{ route('skill_add') }}" method="post">
            @csrf
            <h3>Adicionar Habilidade</h3>
            <label for="skills">Habilidade</label>
            <select name="skill" id="skills" required>
                <option disabled selected>Selecione uma habilidade</option>
                <optgroup label="Linguagens">
                    @foreach ($skillsJson['languages'] as $code => $language)
                        <option value="{{ $code }}">{{ $language['name'] }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Frameworks">
                    @foreach ($skillsJson['frameworks'] as $code => $framework)
                        <option value="{{ $code }}">{{ $framework['name'] }}</option>
                    @endforeach
                </optgroup>
            </select>

            <label for="year">Ano</label>
            <input required type="number" name="year" min="2000" max="2100" placeholder="Year">

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
            @foreach ($skills as $skill)
                @foreach ($skillsJson as $type)
                    @foreach ($type as $code => $language)
                        @if ($code == $skill->code)
                            <tr>
                                <td>
                                    <span class="icon"><i
                                            class="{{ $language['icon'] }}"></i></span><br>
                                    <span>{{ $language['name'] }}</span>
                                </td>
                                <td>{{ $skill->year }}</td>
                                <td>{{ $language['description_pt'] }}</td>
                                <td><a href="delete/{{$code}}">Remover</a></td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @endforeach

        </tbody>
    </table>
@endsection
