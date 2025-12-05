<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncubationRecommendation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incubation_recommendations';

    protected $fillable = [
        'poultry_type',
        'breed',
        'temperature',
        'humidity',
        'incubation_days',
        'egg_size',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'incubation_days' => 'integer',
        'is_active' => 'boolean',
    ];
}
