<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', env('APP_TITLE'))</title>

    @include('partials.favicon')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aldrich">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/resize.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/icons.css">

    <script src="/js/icons.js"></script>
    <meta property="og:url" content="{{ env('APP_URL') }}">

    <meta property="og:description" content="{{ $infos['description'] ?? 'Portfólio em construção' }}">
    <meta name="description" content="{{ $infos['description'] ?? 'Portfólio em construção' }}">

    <meta property="og:image" content="{{ env('APP_URL') }}/img/share.png">
    <meta property="og:image:width" content="2560">
    <meta property="og:image:height" content="500">

    <meta name="theme-color" content="{{ env('THEME_COLOR') }}">

    @include('partials.theme-config')
</head>

<body>
    @if (isset($isNotConfigurad) || isset($portfolioDisabled))
        <main class="container">
            @yield('main')
        </main>
        <script src="/js/theme.js"></script>
    @else
        <header class="header" id="header">
            <a href="/" class="logoname"></a>
            <button id="close" class="btn not-visible"><i class="fa fa-solid fa-xmark"></i></button>
            <button id="open" class="btn"><i class="fa fa-solid fa-bars"></i></button>
            <nav id="navBar" class="not-visible">
                <ul class="list" id="listNav">
                    <li><a href="/#sobre-mim" id="lk-sobre">{{ __('site.head.1') }}</a></li>
                    <li><a href="/#portfolio" id="lk-portfolio">{{ __('site.head.2') }}</a></li>
                    <li><a href="/#habilidades" id="lk-habilidades">{{ __('site.head.3') }}</a></li>
                    <li><a href="/#contato" id="lk-contato">{{ __('site.head.4') }}</a></li>
                    <li>
                        <button class="theme-toggle site-theme-toggle" type="button" aria-label="Alternar tema">
                            <i class="fa-solid fa-moon"></i>
                        </button>
                    </li>
                </ul>

                <ul class="list-social-midia">
                    @if (!is_null($contacts['instagram']))
                        <li>
                            <a class="social" href="https://instagram.com/{{ $contacts['instagram'] }}">
                                <i class="fa fa-brands fa-instagram"></i>
                            </a>
                        </li>
                    @endif

                    @if (!is_null($contacts['github']))
                        <li>
                            <a class="social" href="https://github.com/{{ $contacts['github'] }}">
                                <i class="fa fa-brands fa-github"></i>
                            </a>
                        </li>
                    @endif

                    @if (!is_null($contacts['linkedin']))
                        <li>
                            <a class="social" href="https://linkedin.com/in/{{ $contacts['linkedin'] }}">
                                <i class="fa fa-brands fa-linkedin"></i>
                            </a>
                        </li>
                    @endif

                    @if (!is_null($contacts['twitter']))
                        <li>
                            <a class="social" href="https://x.com/{{ $contacts['twitter'] }}">
                                <i class="fa fa-brands fa-twitter"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </header>
        <main class="container">
            @yield('main')
        </main>

        <footer>
            <section id="contato">
                <div class="info">
                    <h2>{{ __('site.titles.4') }}</h2>
                    <ul class="contact-list">
                        @if (!is_null($contacts['email_personal']))
                            <li title="{{ __('site.other.email_personal') }}">
                                <span><i class="fa-regular fa-envelope"></i></span>
                                <a
                                    href="mailto:{{ $contacts['email_personal'] }}">{{ $contacts['email_personal'] }}</a>
                            </li>
                        @endif
                        @if (!is_null($contacts['email_business']))
                            <li title="{{ __('site.other.email_business') }}">
                                <span><i class="fa-sharp fa-regular fa-building"></i></span>
                                <a
                                    href="mailto:{{ $contacts['email_business'] }}">{{ $contacts['email_business'] }}</a>
                            </li>
                        @endif
                        @if (!is_null($contacts['tel']))
                            <li title="{{ __('site.other.telephone') }}">
                                <span><i class="fa-regular fa-mobile-notch"></i></span>
                                <a href="tel:{{ $contacts['tel'] }}">{{ $contacts['tel'] }}</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="image">
                    <img src="/img/celular.png" alt="smartphone">
                </div>
            </section>

            <section class="main-footer">
                <div class="left-footer">
                    <div class="logoname"></div>
                    <p>&copy; {{ date('Y') }} || {{ $infos['fullname'] }}</p>
                    <p class="saying">{{ $quote }} ~{{ $author }}</p>
                </div>
                <div class="right-footer">
                    <ul class="list-social-midia">
                        @if (!is_null($contacts['instagram']))
                            <li>
                                <a class="social" href="https://instagram.com/{{ $contacts['instagram'] }}">
                                    <i class="fa fa-brands fa-instagram"></i>
                                </a>
                            </li>
                        @endif

                        @if (!is_null($contacts['github']))
                            <li>
                                <a class="social" href="https://github.com/{{ $contacts['github'] }}">
                                    <i class="fa fa-brands fa-github"></i>
                                </a>
                            </li>
                        @endif

                        @if (!is_null($contacts['linkedin']))
                            <li>
                                <a class="social" href="https://linkedin.com/in/{{ $contacts['linkedin'] }}">
                                    <i class="fa fa-brands fa-linkedin"></i>
                                </a>
                            </li>
                        @endif

                        @if (!is_null($contacts['twitter']))
                            <li>
                                <a class="social" href="https://x.com/{{ $contacts['twitter'] }}">
                                    <i class="fa fa-brands fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </section>
        </footer>

        <div class="top-nav">
            @if (!is_null($contacts->csv))
                <a href="{{ $contacts->csv }}" title="{{ __('site.other.cv') }}" class="pdf">
                    <i class="fa-light fa-file"></i>
                </a>
            @endif
            <div id="top-nav"><i class="fa fa-solid fa-angle-up"></i></div>
        </div>

        <script src="/js/theme.js"></script>
        <script src="/js/script.js"></script>
    @endif
</body>

</html>
