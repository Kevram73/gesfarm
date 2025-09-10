<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncubationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'egg_type',
        'breed',
        'egg_count',
        'start_date',
        'incubation_days',
        'temperature',
        'humidity_percentage',
        'egg_size',
        'hatched_count',
        'unhatched_count',
        'hatch_rate',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'temperature' => 'decimal:1',
        'humidity_percentage' => 'decimal:1',
        'hatch_rate' => 'decimal:2',
    ];

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
