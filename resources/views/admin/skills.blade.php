@extends('layouts.admin')
@section('title', 'Habilidades')
@section('content')
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
                    <td>{{$item->language}}</td>
                    <td>{{$item->year}}</td>
                    <td>{{$item->info}}</td>
                    <td>
                        <a href="">Remover</a>
                        <a href="">Editar</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
