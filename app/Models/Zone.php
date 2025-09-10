<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'coordinates',
        'area',
        'status',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'area' => 'decimal:2',
    ];

    public function poultryFlocks(): HasMany
    {
        return $this->hasMany(PoultryFlock::class);
    }

    public function cattle(): HasMany
    {
        return $this->hasMany(Cattle::class);
    }

    public function crops(): HasMany
    {
        return $this->hasMany(Crop::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
