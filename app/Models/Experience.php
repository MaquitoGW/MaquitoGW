<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'company',
        'description',
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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
