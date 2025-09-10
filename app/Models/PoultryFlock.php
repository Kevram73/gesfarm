<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoultryFlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_number',
        'type',
        'breed',
        'initial_quantity',
        'current_quantity',
        'arrival_date',
        'age_days',
        'zone_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'arrival_date' => 'date',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(PoultryRecord::class, 'flock_id');
    }

    public function getMortalityRateAttribute(): float
    {
        if ($this->initial_quantity == 0) return 0;
        return (($this->initial_quantity - $this->current_quantity) / $this->initial_quantity) * 100;
    }
}
