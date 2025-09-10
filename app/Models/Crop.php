<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'variety',
        'zone_id',
        'planting_date',
        'expected_harvest_date',
        'actual_harvest_date',
        'planted_area',
        'expected_yield',
        'actual_yield',
        'status',
        'notes',
    ];

    protected $casts = [
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
        'actual_harvest_date' => 'date',
        'planted_area' => 'decimal:2',
        'expected_yield' => 'decimal:2',
        'actual_yield' => 'decimal:2',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CropActivity::class);
    }

    public function getYieldPerSquareMeterAttribute(): float
    {
        if ($this->planted_area == 0) return 0;
        return $this->actual_yield / $this->planted_area;
    }
}
