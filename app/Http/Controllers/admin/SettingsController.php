<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SettingsController extends AdminController
{
    public function index()
    {
        return view('admin.settings', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'serverSettings' => $this->envValues(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app-name' => 'required|string|max:255',
            'app-env' => 'required|in:local,production,staging',
            'app-debug' => 'required|in:true,false',
            'app-timezone' => 'required|string|max:80',
            'app-faker-locale' => 'required|in:en_US,pt_BR',
            'multiple-languages' => 'required|in:0,1',
            'project-translation-enabled' => 'required|in:true,false',
            'project-translation-provider' => 'required|in:google',
            'google-translate-url' => 'required|url|max:500',
        ]);

        $envPath = base_path('.env');
        $env = File::exists($envPath) ? File::get($envPath) : '';
        $language = explode('_', $validated['app-faker-locale'])[0];

        $values = [
            'APP_TITLE' => [$validated['app-name'], true],
            'APP_NAME' => [$validated['app-name'], true],
            'APP_ENV' => [$validated['app-env'], false],
            'APP_DEBUG' => [$validated['app-debug'], false],
            'APP_TIMEZONE' => [$validated['app-timezone'], false],
            'APP_FAKER_LOCALE' => [$validated['app-faker-locale'], false],
            'APP_LOCALE' => [$language, false],
            'APP_FALLBACK_LOCALE' => [$language, false],
            'MULTIPLE_LANGUAGES' => [$validated['multiple-languages'], false],
            'PROJECT_TRANSLATION_ENABLED' => [$validated['project-translation-enabled'], false],
            'PROJECT_TRANSLATION_PROVIDER' => [$validated['project-translation-provider'], false],
            'GOOGLE_TRANSLATE_URL' => [$validated['google-translate-url'], true],
        ];

        foreach ($values as $key => [$value, $quote]) {
            $env = $this->setEnvValue($env, $key, $value, $quote);
        }

        File::put($envPath, $env);

        config([
            'app.name' => $validated['app-name'],
            'app.env' => $validated['app-env'],
            'app.debug' => $validated['app-debug'] === 'true',
            'app.timezone' => $validated['app-timezone'],
            'app.locale' => $language,
            'app.fallback_locale' => $language,
            'app.faker_locale' => $validated['app-faker-locale'],
        ]);

        Artisan::call('config:clear');

        return redirect()->back()->with('success', 'Configuracoes atualizadas com sucesso!');
    }

    private function setEnvValue(string $env, string $key, ?string $value, bool $quote = false): string
    {
        $value = (string) $value;
        $formattedValue = $quote ? '"' . str_replace(['\\', '"'], ['\\\\', '\"'], $value) . '"' : $value;
        $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';

        if (preg_match($pattern, $env)) {
            return preg_replace($pattern, "{$key}={$formattedValue}", $env);
        }

        return rtrim($env) . PHP_EOL . "{$key}={$formattedValue}" . PHP_EOL;
    }

    private function envValues(): array
    {
        $env = File::exists(base_path('.env')) ? File::get(base_path('.env')) : '';
        $keys = [
            'APP_TITLE',
            'APP_ENV',
            'APP_DEBUG',
            'APP_TIMEZONE',
            'APP_FAKER_LOCALE',
            'MULTIPLE_LANGUAGES',
            'PROJECT_TRANSLATION_ENABLED',
            'PROJECT_TRANSLATION_PROVIDER',
            'GOOGLE_TRANSLATE_URL',
        ];

        return collect($keys)
            ->mapWithKeys(fn($key) => [$key => $this->getEnvValue($env, $key)])
            ->all();
    }

    private function getEnvValue(string $env, string $key): ?string
    {
        if (!preg_match('/^' . preg_quote($key, '/') . '=(.*)$/m', $env, $matches)) {
            return null;
        }

        return Str::of($matches[1])->trim()->trim('"')->trim("'")->toString();
    }
}
