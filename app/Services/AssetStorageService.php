<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        return env('FILE_STORAGE_DRIVER', 'local') === 'r2' ? 'r2' : 'local';
    }

    private function publicUrl(string $path): string
    {
        $baseUrl = $this->r2PublicUrl();

        if ($baseUrl) {
            return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        }

        return Storage::disk('r2')->url($path);
    }

    private function r2PublicUrl(): ?string
    {
        return env('R2_PUBLIC_URL') ? rtrim((string) env('R2_PUBLIC_URL'), '/') : null;
    }

    private function r2Path(string $path): string
    {
        $prefix = trim((string) env('R2_PREFIX', ''), '/');
        $path = trim($path, '/');

        return $prefix !== '' ? $prefix . '/' . $path : $path;
    }
}
