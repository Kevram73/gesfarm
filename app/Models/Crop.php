<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crop extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crops';

    protected $fillable = [
        'farm_id',
        'name',
        'type',
        'variety',
        'category',
        'description',
        'planting_season',
        'harvest_season',
        'growth_period',
        'water_needs',
        'soil_requirements',
        'price_per_unit',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }

    public function fieldCrops()
    {
        return $this->hasMany(FieldCrop::class);
    }
}
