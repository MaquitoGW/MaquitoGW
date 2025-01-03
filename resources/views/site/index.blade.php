@extends('layouts.site')
@section('title', env('APP_TITLE'))
@section('main')
    <section id="index">
        <div class="left-index">
            <p class="col1">Hello world ☝️🤓,</p>
            <p class="col2">{{ __('site.other.welcome') }} {{ $infos['name'] }},</p>
            <p id="write" class="col3" aria-value="{{ __('site.other.training') }}"></p>
            <a class="col-button" href="#contato">{{ __('site.other.button') }}</a>
        </div>
        <div class="right-index">
            <img src="/img/bk.png" class="background" alt="background">
            <img src="/img/bk_logo.png" class="background-logo" alt="background-logo">
        </div>
    </section>

    <section id="sobre-mim">
        <div class="avatar"><img src="{{ $customization('myphoto', '/img/my.jpg') }}" alt="avatar"></div>
        <div class="left">
            <h2>{{ __('site.titles.1') }}</h2>
            <p>{{ $infos['description'] }}</p>
        </div>
    </section>

    <section id="portfolio">
        <h2><span>{{ __('site.titles.2') }}</span></h2>
        <div class="flex-box">

            @foreach ($projects as $item)
                <div class="project">
                    <div class="slide">

                        @php
                            // Decodifica o JSON para um array
                            $images = json_decode($item['images'], true);
                            $count = 1;
                        @endphp

                        <div class="img-none" title="Imagens do Projeto"></div>

                        @if ($images)
                            @foreach ($images as $image)
                                @if ($count == 2)
                                    <img src="{{ $image }}" class="img2" alt="image-project">
                                @elseif($count == 1)
                                    <img src="{{ $image }}" class="img" alt="image-project">
                                @endif
                                @php $count++ @endphp
                            @endforeach
                        @endif
                    </div>

                    <h4>{{ $item['name'] }}</h4>

                    <div class="project-head">
                        <p class="description">
                            <span>{{ $item['preview'] }}</span>
                        </p>
                        <ul class="tags" title="Habilidades utilizadas">
                            @foreach ($skills as $skill)
                            @endforeach

                            @php
                                // Decodifica o JSON para um array
                                $skillsProjects = json_decode($item['skills'], true);
                            @endphp

                            @if ($skillsProjects)
                                @foreach ($skillsProjects as $skill)
                                    @foreach ($skillsJson as $type)
                                        @foreach ($type as $code => $e)
                                            @if ($code == $skill)
                                                <li title="{{ $e['name'] }}"><i class="{{ $e['icon'] }}"></i></li>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endif
                        </ul>
                        <label for="options" class="options">
                            <a title="Demo projeto" class="button" href="demo/{{ $item['demo'] }}/">
                                <i class="fa-solid fa-cube"></i>
                            </a>
                            <a title="Vizualizar informações do projeto" class="button"
                                href="details/{{ $item['demo'] }}/">
                                <i class="fa fa-solid fa-eye"></i>
                            </a>
                            <a title="Repositório GitHub" class="button" href="{{ $item['github'] }}">
                                <i class="fa fa-brands fa-github"></i>
                            </a>
                        </label>
                    </div>
                </div>
            @endforeach

            <div class="project">
                <div class="in">
                    <span><i class="fa-solid fa-graduation-cap"></i></span>
                    <h3>Em breve...</h3>
                    <p>Aprendizado constante!</p>
                    <p>Em breve muitos outros projetos.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="habilidades">
        <h2><span>{{ __('site.titles.3') }}</span></h2>
        <ul class="skills-list">

            @foreach ($skills as $skill)
                @foreach ($skillsJson as $type)
                    @foreach ($type as $code => $language)
                        @if ($code == $skill->code)
                            <li>
                                <label for="{{ $language['name'] }}">
                                    <span><i class="{{ $language['icon'] }}"></i></span>
                                    <b>{{ $language['name'] }}</b>
                                    <p>{{ __('site.other.since') }} {{ $skill->year }}</p>
                                </label>
                            </li>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        </ul>
    </section>

@endsection
