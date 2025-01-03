@extends('layouts.site')
@section('title', env('APP_TITLE'))
@section('main')
    @php
        // Decodifica o JSON para um array
        $skillsProjectsView = json_decode($project['skills'], true);
        $projectsImages = json_decode($project['images'], true);
    @endphp

    <section class="details">
        <a href="/" class="btn-details"><i class="fa fa-solid fa-xmark"></i></a>
        <h2>{{ $project['name'] }}</h2>
        <div class="elements">
            <div class="data">
                <div class="preview">
                    <video class="view visible_" id="player" controls src="/{{ $project['videos'] }}"></video>

                    @php $i = 1; @endphp
                    @foreach ($projectsImages as $image)
                        <img class="view" src="/{{ $image }}" id="{{$i}}" alt="image">
                        @php $i++; @endphp
                    @endforeach
                </div>

                <div class="images">
                    <label class="view visible_" for="video" view="video"><video preload="metadata" src="/{{ $project['videos'] }}"></video></label>
                    @php $i = 1; @endphp
                    @foreach ($projectsImages as $image)
                        <label class="view" view="image" tag="{{$i}}" for="image"><img src="/{{ $image }}" alt="image"></label>
                        @php $i++; @endphp
                    @endforeach
                </div>
            </div>
            <div class="data">
                <h4 class="description">{{__('site.details.description')}}</h4>
                <div class="description">
                    {!! $project['description'] !!}
                </div>

                <a href="/demo/{{ $project['demo'] }}/">{{__('site.details.demo')}}</a>
            </div>
        </div>

        <div class="tag-linguagens">
            <h4>{{__('site.details.skills')}}</h4>
            <ul class="tags" title="{{__('site.details.skills')}}">

                @if ($skillsProjectsView)
                    @foreach ($skillsProjectsView as $skill)
                        @foreach ($skillsJson as $type)
                            @foreach ($type as $code => $e)
                                @if ($code == $skill)
                                    <li title="{{ $e['name'] }}">{{ $e['name'] }}</li>
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
            </ul>
        </div>
    </section>

    <link rel="stylesheet" href="/css/player.css">
    <script src="/js/player.js"></script>
    <script>
        new playerAwe('#player');
    </script>
@endsection
