@php
    $faviconConfig = \App\Models\Customization::where('config', 'favicon')->first();
    $faviconPath = $faviconConfig
        ? ($faviconConfig->encode ? base64_decode($faviconConfig->value) : $faviconConfig->value)
        : '/img/favicon.png';
@endphp

<link rel="shortcut icon" href="{{ $faviconPath }}" type="image/png">
<link rel="icon" href="{{ $faviconPath }}" type="image/png">
