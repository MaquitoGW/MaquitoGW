@php
    $primaryColor = $customization('color_primary', '#6200ff');
    $secondaryColor = $customization('color_secondary', '#8400ff');
    $themeSelection = $customization('theme_mode', $customization('admin_theme', $customization('theme_selection', 'system')));

    $hexToRgb = function ($hex) {
        $hex = ltrim((string) $hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            return '98, 0, 255';
        }

        return hexdec(substr($hex, 0, 2)) . ', ' . hexdec(substr($hex, 2, 2)) . ', ' . hexdec(substr($hex, 4, 2));
    };

    $skillMap = collect($skillsJson['languages'] ?? [])
        ->merge($skillsJson['frameworks'] ?? [])
        ->mapWithKeys(fn($item, $code) => [$code => $item['name'] ?? $code])
        ->all();

    $projectsData = $projects->values()->map(function ($project, $index) use ($skillMap) {
        $images = collect(json_decode($project->images ?? '[]', true) ?: [])
            ->filter()
            ->map(fn($image) => str_starts_with($image, 'http') ? $image : '/' . ltrim($image, '/'))
            ->values()
            ->all();
        $skillCodes = json_decode($project->skills ?? '[]', true) ?: [];
        $tags = collect($skillCodes)->map(fn($code) => $skillMap[$code] ?? $code)->values()->all();
        $image = count($images) > 0 ? $images[0] : 'https://via.placeholder.com/800x600?text=Project';

        return [
            'id' => $project->id ?? $index + 1,
            'title' => $project->localizedName(),
            'description' => $project->localizedPreview(),
            'image' => $image,
            'images' => $images,
            'video' => !empty($project->videos) ? (str_starts_with($project->videos, 'http') ? $project->videos : '/' . ltrim($project->videos, '/')) : null,
            'tags' => $tags,
            'github' => $project->github,
            'demo' => !empty($project->demo) ? url('/demo/' . $project->demo) : null,
            'longDescription' => $project->localizedDescription() ?: $project->localizedPreview(),
        ];
    })->all();

    $minimalistTranslations = [
        'viewCode' => __('site.minimalist.view_code'),
        'liveDemo' => __('site.minimalist.live_demo'),
        'viewImage' => __('site.minimalist.view_image'),
        'contactSuccessTitle' => __('site.minimalist.contact_success_title'),
        'contactErrorTitle' => __('site.minimalist.contact_error_title'),
        'cookieTitle' => __('site.minimalist.cookie_title'),
        'cookieText' => __('site.minimalist.cookie_text'),
        'cookieAccept' => __('site.minimalist.cookie_accept'),
    ];

    $contactErrors = $errors->any() ? $errors->all() : [];
@endphp

<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{{ $infos['description'] }}">
    <meta name="description" content="{{ $infos['description'] }}">
    <meta property="og:image" content="{{ env('APP_URL') }}/img/share.png">
    <meta property="og:image:width" content="2560">
    <meta property="og:image:height" content="500">
    <meta name="theme-color" content="{{ $primaryColor }}">
    @include('partials.favicon')
    <link rel="stylesheet" href="css/new_desiner.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Montserrat:wght@300;400;600;700&family=Roboto:wght@300;400;700&family=Lobster&family=Playfair+Display:wght@400;700&family=Cinzel:wght@400;700&family=Raleway:wght@300;400;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-color: {{ $primaryColor }};
            --primary-rgb: {{ $hexToRgb($primaryColor) }};
            --ring: {{ $secondaryColor }};
            --github-accent: {{ $secondaryColor }};
        }
    </style>
    @include('partials.theme-config')
    <script>
        window.minimalistTheme = @json($themeSelection);
        window.minimalistTranslations = {!! json_encode($minimalistTranslations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        window.minimalistContactFeedback = {
            success: @json(session('contact_success')),
            errors: @json($contactErrors),
        };
    </script>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="#" class="logo">
                {!! $search('custom_logo_code', 'MinhaLogo') !!}
            </a>

            <!-- Desktop Navigation -->
            <nav class="nav-desktop">
                <a href="#about" class="nav-link">{{ __('site.head.1') }}</a>
                <a href="#projects" class="nav-link">{{ __('site.head.2') }}</a>
                <a href="#skills" class="nav-link">{{ __('site.head.3') }}</a>
                @if (isset($experiences) && $experiences->count() > 0)
                    <a href="#experience" class="nav-link">{{ __('site.minimalist.experience_nav') }}</a>
                @endif
                <a href="#contact" class="nav-link">{{ __('site.head.4') }}</a>
                <button class="theme-toggle" aria-label="Toggle theme">
                    <i class="fas fa-moon"></i>
                </button>
            </nav>

            <!-- Mobile Navigation Toggle -->
            <div class="mobile-nav-toggle">
                <button class="theme-toggle" aria-label="Toggle theme">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="menu-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="mobile-nav">
            <div class="container">
                <nav class="nav-mobile">
                    {{-- <a href="#home" class="nav-link">Home</a> --}}
                    <a href="#about" class="nav-link">{{ __('site.head.1') }}</a>
                    <a href="#projects" class="nav-link">{{ __('site.head.2') }}</a>
                    <a href="#skills" class="nav-link">{{ __('site.head.3') }}</a>
                    @if (isset($experiences) && $experiences->count() > 0)
                        <a href="#experience" class="nav-link">{{ __('site.minimalist.experience_nav') }}</a>
                    @endif
                    <a href="#contact" class="nav-link">{{ __('site.head.4') }}</a>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('main')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div class="footer-logo">
                    <a href="#" class="logo">
                        {!! $search('custom_logo_code', 'MinhaLogo') !!}
                    </a>
                    <p>{{ $quote }} ~{{ $author }}</p>
                </div>

                <div class="footer-social">
                    @if (!is_null($contacts['instagram']))
                        <a href="https://instagram.com/{{ $contacts['instagram'] }}" target="_blank"
                            rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fa fa-brands fa-instagram"></i>
                        </a>
                    @endif

                    @if (!is_null($contacts['github']))
                        <a href="https://github.com/{{ $contacts['github'] }}" target="_blank"
                            rel="noopener noreferrer" aria-label="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif

                    @if (!is_null($contacts['linkedin']))
                        <a href="https://linkedin.com/in/{{ $contacts['linkedin'] }}" target="_blank"
                            rel="noopener noreferrer" aria-label="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif

                    @if (!is_null($contacts['twitter']))
                        <a href="https://x.com/{{ $contacts['twitter'] }}" target="_blank" rel="noopener noreferrer"
                            aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                </div>

                <div class="footer-copyright">
                    &copy; <span id="current-year">{{ date('Y') }}</span> {{ $infos['fullname'] }}
                </div>
            </div>

            <div class="footer-bottom">
                <nav class="footer-nav">
                    {{-- <a href="#home">Home</a> --}}
                    <a href="#about">{{ __('site.head.1') }}</a>
                    <a href="#projects">{{ __('site.head.2') }}</a>
                    <a href="#skills">{{ __('site.head.3') }}</a>
                    @if (isset($experiences) && $experiences->count() > 0)
                        <a href="#experience">{{ __('site.minimalist.experience_nav') }}</a>
                    @endif
                    <a href="#contact">{{ __('site.head.4') }}</a>
                </nav>
            </div>
        </div>
    </footer>

    <div class="site-toast" id="contact-toast" aria-live="polite" aria-hidden="true">
        <div class="site-toast-icon">
            <i class="fas fa-circle-check"></i>
        </div>
        <div>
            <strong class="site-toast-title"></strong>
            <p class="site-toast-message"></p>
            <ul class="site-toast-list"></ul>
        </div>
        <button class="site-toast-close" type="button" aria-label="{{ __('site.minimalist.close') }}">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="cookie-popup" id="cookie-popup" aria-hidden="true">
        <div class="cookie-popup-icon">
            <i class="fas fa-cookie-bite"></i>
        </div>
        <div class="cookie-popup-content">
            <strong>{{ __('site.minimalist.cookie_title') }}</strong>
            <p>{{ __('site.minimalist.cookie_text') }}</p>
        </div>
        <button type="button" class="btn btn-primary cookie-accept" id="cookie-accept">
            {{ __('site.minimalist.cookie_accept') }}
        </button>
    </div>

    <script>
        const projectsData = @json($projectsData);
    </script>

    <script src="/js/theme.js"></script>
    <script src="/js/new_desiner.js"></script>
</body>

</html>
