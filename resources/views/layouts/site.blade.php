<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Aldrich" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/resize.css">
    <link rel="stylesheet" href="/icons/css/all.css">
    <script src="/icons/js/all.js"></script>
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{{ $infos['description'] }}">
    <meta name="description" content="{{ $infos['description'] }}">
    <meta property="og:image" content="{{ env('APP_URL') }}/img/share.png">
    <meta property="og:image:width" content="2560">
    <meta property="og:image:height" content="500">
    <meta name="theme-color" content="{{ env('THEME_COLOR') }}">
</head>

<body>

    <header class="header" id="header">
        <a href="#" class="logoname"></a>
        <button id="close" class="btn not-visible"><i class="fa fa-solid fa-xmark"></i></button>
        <button id="open" class="btn"><i class="fa fa-solid fa-bars"></i></button>
        <nav id="navBar" class="not-visible">
            <ul class="list" id="listNav">
                <li><a href="#sobre-mim" id="lk-sobre">Sobre Mim</a></li>
                <li><a href="#portfolio" id="lk-portfolio">Projetos</a></li>
                <li><a href="#habilidades" id="lk-habilidades">Habilidades</a></li>
                <li><a href="#contato" id="lk-contato">Contato</a></li>
            </ul>

            <ul class="list-social-midia">
                <li>
                    <a class="social" href="https://instagram.com/{{ $contacts['instagram'] }}">
                        <i class="fa fa-brands fa-instagram"></i>
                    </a>
                </li>
                <li>
                    <a class="social" href="https://github.com/{{ $contacts['github'] }}">
                        <i class="fa fa-brands fa-github"></i>
                    </a>
                </li>
                <li>
                    <a class="social" href="https://linkedin.com/in/{{ $contacts['linkedin'] }}">
                        <i class="fa fa-brands fa-linkedin"></i>
                    </a>
                </li>
                <li>
                    <a class="social" href="https://x.com/{{ $contacts['twitter'] }}">
                        <i class="fa fa-brands fa-twitter"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="container">
        @yield('main')
    </main>

    <footer>
        <section id="contato">
            <div class="info">
                <h2>Entre em contato!</h2>
                <ul class="contact-list">
                    <li title="Email pessoal">
                        <span><i class="fa-regular fa-envelope"></i></span>
                        <a href="mailto:{{ $contacts['email_personal'] }}">{{ $contacts['email_personal'] }}</a>
                    </li>
                    <li title="Email empresarial">
                        <span><i class="fa-sharp fa-regular fa-building"></i></span>
                        <a href="mailto:{{ $contacts['email_business'] }}">{{ $contacts['email_business'] }}</a>
                    </li>
                    <li title="Telefone de contato">
                        <span><i class="fa-regular fa-mobile-notch"></i></span>
                        <a href="tel:{{ $contacts['tel'] }}">{{ $contacts['tel'] }}</a>
                    </li>
                </ul>
            </div>

            <div class="image">
                <img src="/img/celular.png" alt="smartphone">
            </div>
        </section>

        <section class="main-footer">
            <div class="left-footer">
                <div class="logoname"></div>
                <p>&copy; {{ date('Y') }} por {{ $infos['fullname'] }}</p>
                <p class="saying">{{ $frase }} ~{{ $autor }}</p>
            </div>
            <div class="right-footer">
                <ul class="list-social-midia">
                    <li>
                        <a class="social" href="https://instagram.com/{{ $contacts['instagram'] }}">
                            <i class="fa fa-brands fa-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a class="social" href="https://github.com/{{ $contacts['github'] }}">
                            <i class="fa fa-brands fa-github"></i>
                        </a>
                    </li>
                    <li>
                        <a class="social" href="https://linkedin.com/in/{{ $contacts['linkedin'] }}">
                            <i class="fa fa-brands fa-linkedin"></i>
                        </a>
                    </li>
                    <li>
                        <a class="social" href="https://x.com/{{ $contacts['twitter'] }}">
                            <i class="fa fa-brands fa-twitter"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </section>
    </footer>

    <div id="top-nav"><i class="fa fa-solid fa-angle-up"></i></div>

    <script src="/js/script.js"></script>
</body>

</html>
