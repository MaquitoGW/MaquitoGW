@php
    $themeConfigValue = function ($config, $fallback = null) {
        $item = \App\Models\Customization::where('config', $config)->first();

        if (!$item) {
            return $fallback;
        }

        return $item->encode ? base64_decode($item->value) : $item->value;
    };

    $themeMode = $themeConfigValue('theme_mode', $themeConfigValue('admin_theme', $themeConfigValue('theme_selection', 'system')));
    $hexToRgb = function ($hex, $fallback = '47, 129, 247') {
        $hex = ltrim((string) $hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            return $fallback;
        }

        return hexdec(substr($hex, 0, 2)) . ', ' . hexdec(substr($hex, 2, 2)) . ', ' . hexdec(substr($hex, 4, 2));
    };
    $configuredPrimary = $themeConfigValue('color_primary');
    $configuredSecondary = $themeConfigValue('color_secondary');
    $themeCssVars = [
        '--primary-color' => $configuredPrimary,
        '--primary-dark' => $configuredSecondary,
        '--primary-light' => $themeConfigValue('theme_color_primary_light', $themeConfigValue('admin_color_primary_light')),
        '--primary' => $configuredPrimary,
        '--ring' => $configuredSecondary,
        '--github-accent' => $configuredSecondary,
        '--theme-primary-rgb' => $configuredPrimary ? $hexToRgb($configuredPrimary) : null,
        '--theme-secondary-rgb' => $configuredSecondary ? $hexToRgb($configuredSecondary, '31, 111, 235') : null,
        '--text-light' => $themeConfigValue('theme_color_text'),
        '--foreground' => $themeConfigValue('theme_color_text'),
        '--white-color' => $themeConfigValue('theme_color_heading'),
        '--text-secondary' => $themeConfigValue('theme_color_muted'),
        '--muted-foreground' => $themeConfigValue('theme_color_muted'),
        '--button-text-color' => $themeConfigValue('theme_color_button_text'),
        '--primary-foreground' => $themeConfigValue('theme_color_button_text'),
        '--border-color' => $themeConfigValue('theme_color_border'),
        '--border' => $themeConfigValue('theme_color_border'),
    ];
    $themeInlineStyle = collect($themeCssVars)
        ->filter(fn($value) => filled($value))
        ->map(fn($value, $key) => $key . ': ' . $value . ' !important;')
        ->implode(' ');
@endphp

<style>
    :root.app-theme-configured,
    body.app-theme-configured {
        {!! $themeInlineStyle !!}
    }
</style>
<script>
    window.appThemeConfig = {
        mode: @json($themeMode ?: 'system')
    };
</script>
