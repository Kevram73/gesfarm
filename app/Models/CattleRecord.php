<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CattleRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'cattle_id',
        'record_date',
        'milk_production',
        'weight',
        'health_status',
        'health_notes',
        'feeding_notes',
        'observations',
        'recorded_by',
    ];

    protected $casts = [
        'record_date' => 'date',
        'milk_production' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function cattle(): BelongsTo
    {
        return $this->belongsTo(Cattle::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
