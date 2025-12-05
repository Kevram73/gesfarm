<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fields';

    protected $fillable = [
        'farm_id',
        'name',
        'area',
        'area_used',
        'soil_type',
        'ph_level',
        'fertility',
        'irrigation',
        'location',
        'coordinates',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'area_used' => 'decimal:2',
        'ph_level' => 'decimal:2',
        'coordinates' => 'array',
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

    /**
     * Obtenir la superficie disponible du champ
     */
    public function getAvailableAreaAttribute()
    {
        return max(0, $this->area - $this->area_used);
    }

    /**
     * Vérifier si le champ a de l'espace disponible
     */
    public function hasAvailableArea($requiredArea = 0)
    {
        return $this->available_area >= $requiredArea;
    }
}
