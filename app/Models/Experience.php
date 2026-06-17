<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'position_en',
        'company',
        'description',
        'description_en',
        'promotions',
        'promotions_en',
        'skills',
        'location',
        'start_date',
        'end_date',
        'current',
        'position_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current' => 'boolean',
        'promotions' => 'array',
        'promotions_en' => 'array',
        'skills' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function localizedPosition(): string
    {
        return $this->shouldUseEnglish() && $this->position_en
            ? $this->position_en
            : $this->position;
    }

    public function localizedDescription(): ?string
    {
        return $this->shouldUseEnglish() && $this->description_en
            ? $this->description_en
            : $this->description;
    }

    public function localizedPromotions(): array
    {
        return $this->shouldUseEnglish() && is_array($this->promotions_en) && count($this->promotions_en) > 0
            ? $this->promotions_en
            : ($this->promotions ?? []);
    }

    private function shouldUseEnglish(): bool
    {
        return env('MULTIPLE_LANGUAGES') == 1 && str_replace('-', '_', App::getLocale()) === 'en_US';
    }
}
