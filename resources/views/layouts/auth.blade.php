<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Nunito" />
    <link rel="stylesheet" href="/css/auth.css">
    <link rel="shortcut icon" href="/img/favicon.png" type="image/png">
</head>

<body>
    <main class="login-container">
        <section class="login">
            <video src="/img/bg.mp4" autoplay="true" muted loop></video>
            @yield('content')
        </section>
    </main>

    <footer>
        {{ env('APP_NAME') }} @ {{ date('Y') }} | <a href="{{ env('APP_URL') }}"
            target="_blank">{{ env('APP_DOMINE') }}</a>
    </footer>
</body>

</html>
