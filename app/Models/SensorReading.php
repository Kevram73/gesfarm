<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'value',
        'unit',
        'reading_time',
        'metadata',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'reading_time' => 'datetime',
        'metadata' => 'array',
    ];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCritical($query)
    {
        return $query->where('status', 'critical');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('reading_time', [$startDate, $endDate]);
    }

    public function scopeLatest($query, $limit = 100)
    {
        return $query->orderBy('reading_time', 'desc')->limit($limit);
    }
}
