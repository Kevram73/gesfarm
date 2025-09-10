<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoultryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'flock_id',
        'record_date',
        'eggs_collected',
        'feed_consumed',
        'mortality_count',
        'average_weight',
        'health_notes',
        'observations',
        'recorded_by',
    ];

    protected $casts = [
        'record_date' => 'date',
        'feed_consumed' => 'decimal:2',
        'average_weight' => 'decimal:2',
    ];

    public function flock(): BelongsTo
    {
        return $this->belongsTo(PoultryFlock::class, 'flock_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
