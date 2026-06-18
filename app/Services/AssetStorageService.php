<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class AssetStorageService
{
    public function putUploadedFile(UploadedFile $file, string $directory, ?string $name = null): string
    {
        $name ??= $this->fileName($file->getClientOriginalName(), $file->getClientOriginalExtension());

        return $this->putContent($file->get(), $directory, $name);
    }

    public function putContent(string $content, string $directory, string $name): string
    {
        $directory = trim($directory, '/');
        $name = ltrim($name, '/');

        if ($this->disk() === 'r2') {
            $this->configureR2Disk();
            $path = $this->r2Path(trim($directory . '/' . $name, '/'));
            Storage::disk('r2')->put($path, $content, ['visibility' => 'public']);

            return $this->publicUrl($path);
        }

        $path = public_path('storage/' . $directory);
        File::ensureDirectoryExists($path);
        File::put($path . DIRECTORY_SEPARATOR . $name, $content);

        return '/storage/' . trim($directory . '/' . $name, '/');
    }

    public function delete(?string $urlOrPath): void
    {
        $urlOrPath = trim((string) $urlOrPath);

        if ($urlOrPath === '') {
            return;
        }

        if (Str::startsWith($urlOrPath, ['http://', 'https://']) && $this->r2PublicUrl()) {
            $path = trim(Str::after($urlOrPath, rtrim($this->r2PublicUrl(), '/') . '/'), '/');

            if ($path !== $urlOrPath) {
                $this->configureR2Disk();
                Storage::disk('r2')->delete($path);
            }

            return;
        }

        File::delete(public_path(ltrim($urlOrPath, '/')));
    }

    public function fileName(string $originalName, ?string $extension = null): string
    {
        $extension = $extension ?: pathinfo($originalName, PATHINFO_EXTENSION);

        return md5($originalName . microtime(true) . random_int(1000, 9999)) . ($extension ? '.' . $extension : '');
    }

    public function disk(): string
    {
        return $this->setting('FILE_STORAGE_DRIVER', env('FILE_STORAGE_DRIVER', 'local')) === 'r2' ? 'r2' : 'local';
    }

    private function publicUrl(string $path): string
    {
        $baseUrl = $this->r2PublicUrl();

        if ($baseUrl) {
            return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        }

        $this->configureR2Disk();

        return Storage::disk('r2')->url($path);
    }

    private function r2PublicUrl(): ?string
    {
        $url = $this->setting('R2_PUBLIC_URL', env('R2_PUBLIC_URL'));

        return $url ? rtrim((string) $url, '/') : null;
    }

    private function r2Path(string $path): string
    {
        $prefix = trim((string) $this->setting('R2_PREFIX', env('R2_PREFIX', '')), '/');
        $path = trim($path, '/');

        return $prefix !== '' ? $prefix . '/' . $path : $path;
    }

    private function configureR2Disk(): void
    {
        config([
            'filesystems.disks.r2.key' => $this->setting('R2_ACCESS_KEY_ID', env('R2_ACCESS_KEY_ID')),
            'filesystems.disks.r2.secret' => $this->setting('R2_SECRET_ACCESS_KEY', env('R2_SECRET_ACCESS_KEY')),
            'filesystems.disks.r2.region' => $this->setting('R2_DEFAULT_REGION', env('R2_DEFAULT_REGION', 'auto')) ?: 'auto',
            'filesystems.disks.r2.bucket' => $this->setting('R2_BUCKET', env('R2_BUCKET')),
            'filesystems.disks.r2.url' => $this->setting('R2_PUBLIC_URL', env('R2_PUBLIC_URL')),
            'filesystems.disks.r2.endpoint' => $this->setting('R2_ENDPOINT', env('R2_ENDPOINT')),
            'filesystems.disks.r2.use_path_style_endpoint' => filter_var(env('R2_USE_PATH_STYLE_ENDPOINT', false), FILTER_VALIDATE_BOOLEAN),
        ]);

        if (method_exists(Storage::getFacadeRoot(), 'forgetDisk')) {
            Storage::forgetDisk('r2');
        }
    }

    private function setting(string $key, mixed $fallback = null): mixed
    {
        try {
            if (Schema::hasTable('app_settings')) {
                $value = AppSetting::where('key', $key)->value('value');

                if ($value !== null && $value !== '') {
                    return $value;
                }
            }
        } catch (Throwable) {
            return $fallback;
        }

        return $fallback;
    }
}
