<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'model',
        'serial_number',
        'location',
        'zone_id',
        'configuration',
        'status',
        'last_reading_at',
        'last_reading',
        'battery_level',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'configuration' => 'array',
        'last_reading' => 'array',
        'last_reading_at' => 'datetime',
        'battery_level' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeLowBattery($query, $threshold = 20)
    {
        return $query->where('battery_level', '<', $threshold);
    }

    public function scopeOffline($query, $minutes = 30)
    {
        return $query->where('last_reading_at', '<', now()->subMinutes($minutes));
    }
}
