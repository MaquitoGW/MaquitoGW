<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Project extends Model
{
    use HasFactory;

    public function localizedName(): string
    {
        return $this->localizedField('name');
    }

    public function localizedPreview(): string
    {
        return $this->localizedField('preview');
    }

    public function localizedDescription(): string
    {
        return $this->localizedField('description');
    }

    private function localizedField(string $field): string
    {
        $locale = str_replace('-', '_', App::getLocale());
        $englishField = $field . '_en';

        if (env('MULTIPLE_LANGUAGES') == 1 && $locale === 'en_US' && !empty($this->{$englishField})) {
            return (string) $this->{$englishField};
        }

        return (string) ($this->{$field} ?? '');
    }
}
