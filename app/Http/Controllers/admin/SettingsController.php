<?php

namespace App\Http\Controllers\admin;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
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
            'portfolio-enabled' => 'required|in:true,false',
            'file-storage-driver' => 'required|in:local,r2',
            'r2-endpoint' => 'nullable|url|max:500',
            'r2-access-key-id' => 'nullable|string|max:255',
            'r2-secret-access-key' => 'nullable|string|max:255',
            'r2-bucket' => 'nullable|string|max:255',
            'r2-public-url' => 'nullable|url|max:500',
            'r2-default-region' => 'nullable|string|max:80',
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
            'PORTFOLIO_ENABLED' => [$validated['portfolio-enabled'], false],
            'FILE_STORAGE_DRIVER' => [$validated['file-storage-driver'], false],
            'R2_ENDPOINT' => [$validated['r2-endpoint'] ?? '', true],
            'R2_ACCESS_KEY_ID' => [$validated['r2-access-key-id'] ?? '', true],
            'R2_SECRET_ACCESS_KEY' => [$validated['r2-secret-access-key'] ?? '', true],
            'R2_BUCKET' => [$validated['r2-bucket'] ?? '', true],
            'R2_PUBLIC_URL' => [$validated['r2-public-url'] ?? '', true],
            'R2_DEFAULT_REGION' => [$validated['r2-default-region'] ?? 'auto', false],
        ];

        foreach ($values as $key => [$value, $quote]) {
            $env = $this->setEnvValue($env, $key, $value, $quote);
            $this->saveSetting($key, $value);
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
            'filesystems.asset_disk' => $validated['file-storage-driver'],
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
            'PORTFOLIO_ENABLED',
            'FILE_STORAGE_DRIVER',
            'R2_ENDPOINT',
            'R2_ACCESS_KEY_ID',
            'R2_SECRET_ACCESS_KEY',
            'R2_BUCKET',
            'R2_PUBLIC_URL',
            'R2_DEFAULT_REGION',
        ];

        return collect($keys)
            ->mapWithKeys(fn($key) => [$key => $this->settingValue($key, $this->getEnvValue($env, $key))])
            ->all();
    }

    private function getEnvValue(string $env, string $key): ?string
    {
        if (!preg_match('/^' . preg_quote($key, '/') . '=(.*)$/m', $env, $matches)) {
            return null;
        }

        return Str::of($matches[1])->trim()->trim('"')->trim("'")->toString();
    }

    private function saveSetting(string $key, ?string $value): void
    {
        if (!Schema::hasTable('app_settings')) {
            return;
        }

        AppSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $this->settingType($key)]
        );
    }

    private function settingValue(string $key, ?string $fallback = null): ?string
    {
        if (!Schema::hasTable('app_settings')) {
            return $fallback;
        }

        return AppSetting::where('key', $key)->value('value') ?? $fallback;
    }

    private function settingType(string $key): string
    {
        return in_array($key, ['APP_DEBUG', 'PROJECT_TRANSLATION_ENABLED', 'PORTFOLIO_ENABLED'], true)
            ? 'boolean'
            : 'string';
    }
}
