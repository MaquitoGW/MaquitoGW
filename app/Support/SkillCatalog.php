<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class SkillCatalog
{
    private array $items;

    public function __construct()
    {
        $this->items = $this->load();
    }

    public static function make(): self
    {
        return new self();
    }

    public function grouped(): array
    {
        return $this->items;
    }

    public function flat(): array
    {
        return collect($this->items)
            ->flatMap(fn($group) => $group)
            ->all();
    }

    public function find(?string $code): array
    {
        $code = (string) $code;

        return $this->flat()[$code] ?? [
            'name' => $code,
            'description_pt' => '',
            'description_en' => '',
            'icon' => 'fa-solid fa-code',
        ];
    }

    public function name(?string $code): string
    {
        return $this->find($code)['name'] ?? (string) $code;
    }

    public function icon(?string $code): string
    {
        return $this->find($code)['icon'] ?? 'fa-solid fa-code';
    }

    public function description(?string $code): string
    {
        $skill = $this->find($code);
        $locale = str_replace('-', '_', App::getLocale());
        $field = $locale === 'en_US' ? 'description_en' : 'description_pt';

        return $skill[$field] ?? $skill['description_pt'] ?? '';
    }

    private function load(): array
    {
        foreach ($this->paths() as $path) {
            if (File::exists($path)) {
                $data = json_decode(File::get($path), true);

                if (is_array($data)) {
                    return [
                        'languages' => $data['languages'] ?? [],
                        'frameworks' => $data['frameworks'] ?? [],
                        'software_skills' => $data['software_skills'] ?? [],
                    ];
                }
            }
        }

        return [
            'languages' => [],
            'frameworks' => [],
            'software_skills' => [],
        ];
    }

    private function paths(): array
    {
        return [
            storage_path('app/json/languagens_and_frameworks.json'),
            public_path('storage/json/languagens_and_frameworks.json'),
            base_path('storage/json/languagens_and_frameworks.json'),
        ];
    }
}
