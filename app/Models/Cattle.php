<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cattle extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_number',
        'name',
        'breed',
        'gender',
        'birth_date',
        'mother_tag',
        'father_tag',
        'current_weight',
        'status',
        'zone_id',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'current_weight' => 'decimal:2',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(CattleRecord::class);
    }

    public function getAgeInDaysAttribute(): int
    {
        return $this->birth_date->diffInDays(now());
    }

    public function getAgeInMonthsAttribute(): int
    {
        return $this->birth_date->diffInMonths(now());
    }
}
