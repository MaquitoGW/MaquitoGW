@extends('layouts.site')
@section('title', 'MaquitoGW || Portifólio')
@section('main')
    <section id="index">
        <div class="left-index">
            <p class="col1">Hello world ☝️🤓,</p>
            <p class="col2">{{__('site.other.welcome')}} {{ $infos['name'] }},</p>
            <p id="write" class="col3"></p>
            <a class="col-button" href="#contato">{{__('site.other.button')}}</a>
        </div>
        <div class="right-index">
            <img src="/img/bk.png" class="background" alt="background">
            <img src="/img/bk_logo.png" class="background-logo" alt="background-logo">
        </div>
    </section>

    <section id="sobre-mim">
        <div class="avatar"><img src="{{ $infos['avatar'] }}" alt="avatar"></div>
        <div class="left">
            <h2>{{__('site.titles.1')}}</h2>
            <p>{{ $infos['description'] }}</p>
        </div>
    </section>

    <section id="portfolio">
        <h2><span>{{__('site.titles.2')}}</span></h2>
        <div class="flex-box">

            @foreach ($projects as $item)
                <div class="project">
                    <h4>{{ $item['name'] }}</h4>
                    <div class="slide">

                        @php
                            // Decodifica o JSON para um array
                            $images = json_decode($item['images'], true);
                            $count = 1;
                        @endphp

                        <div class="img-none" title="Imagens do Projeto"></div>

                        @if ($images)
                            @foreach ($images as $image)
                                @if ($count++ == 2)
                                    <img src="{{ $image }}" class="img2" alt="image-project">
                                @else
                                    <img src="{{ $image }}" class="img" alt="image-project">
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="project-head">
                        <p class="description">
                            <span>{{ $item['description'] }}</span>
                            <a title="Mais informações" class="button" href="details/{{ $item['demo'] }}">saber mais...</a>
                        </p>
                        <ul class="tags" title="Habilidades utilizadas">
                            @php
                                // Decodifica o JSON para um array
                                $skills = json_decode($item['skills'], true);
                            @endphp

                            @if ($skills)
                                @foreach ($skills as $skill)
                                    <li class="{{ $skill }}">{{ $skill }}</li>
                                @endforeach
                            @endif
                        </ul>
                        <label for="options" class="options">
                            <a title="Vizualizar projeto" class="button" href="demo/{{ $item['demo'] }}"><i
                                    class="fa fa-solid fa-eye"></i></a>
                            <a title="Repositório GitHub" class="button" href="{{ $item['github'] }}"><i
                                    class="fa fa-brands fa-github"></i></a>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="habilidades">
        <h2><span>{{__('site.titles.3')}}</span></h2>
        <ul class="skills-list">
            <li>
                <label for="PHP">
                    <span><i class="fa-brands fa-php"></i></span>
                    <b>PHP</b>
                    <p>{{__('site.other.since')}} 2022</p>
                </label>
            </li>
            <li>
                <label for="Laravel">
                    <span><i class="fa-brands fa-laravel"></i></span>
                    <b>Laravel</b>
                    <p>{{__('site.other.since')}} 2024</p>
                </label>
            </li>
            <li>
                <label for="JS">
                    <span><i class="fa-brands fa-js"></i></span>
                    <b>JavaScript</b>
                    <p>{{__('site.other.since')}} 2022</p>
                </label>
            </li>
            <li>
                <label for="CSS3">
                    <span><i class="fa-brands fa-css3"></i></span>
                    <b>CSS3</b>
                    <p>{{__('site.other.since')}} 2022</p>
                </label>
            </li>
            <li>
                <label for="HTML5">
                    <span><i class="fa-brands fa-html5"></i></span>
                    <b>HTML5</b>
                    <p>{{__('site.other.since')}} 2022</p>
                </label>
            </li>
        </ul>
    </section>

    @if ($project)
        <section class="details">
            <a href="/" class="btn-details"><i class="fa fa-solid fa-xmark"></i></a>
            <h4>{{ $project['name'] }}</h4>
            <div class="video">
                <video controls src="/{{ $project['videos'] }}"></video>
            </div>
            <div class="description">{{ $project['description'] }}</div>

            <div class="tag-linguagens">
                <h5>Habilidades usadas:</h5>
                <ul class="tags" title="Habilidades utilizadas">
                    @php
                        // Decodifica o JSON para um array
                        $skills = json_decode($project['skills'], true);
                    @endphp

                    @if ($skills)
                        @foreach ($skills as $skill)
                            <li class="{{ $skill }}">{{ $skill }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </section>
    @endif

@endsection
