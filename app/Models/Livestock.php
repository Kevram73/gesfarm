<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livestock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'livestock';

    protected $fillable = [
        'farm_id',
        'parent_id',
        'name',
        'type',
        'breed',
        'age',
        'weight',
        'gender',
        'status',
        'purchase_date',
        'purchase_price',
        'notes',
        'quantity',
        'min_stock',
        'max_stock',
        'is_low_stock',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'max_stock' => 'decimal:2',
        'is_low_stock' => 'boolean',
        'purchase_date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function parent()
    {
        return $this->belongsTo(Livestock::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Livestock::class, 'parent_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(LivestockStockMovement::class);
    }

    public function eggIncubations()
    {
        return $this->hasMany(EggIncubation::class);
    }

    public function chicks()
    {
        return $this->hasMany(Chick::class);
    }

    public function prophylaxis()
    {
        return $this->hasMany(Prophylaxis::class);
    }

    public function poultryFeedRecords()
    {
        return $this->hasMany(PoultryFeedRecord::class);
    }

    public function eggProductions()
    {
        return $this->hasMany(EggProduction::class);
    }

    public function breedingsAsMale()
    {
        return $this->hasMany(Breeding::class, 'male_id');
    }

    public function breedingsAsFemale()
    {
        return $this->hasMany(Breeding::class, 'female_id');
    }

    public function calvingsAsMother()
    {
        return $this->hasMany(Calving::class, 'mother_id');
    }

    public function milkProductions()
    {
        return $this->hasMany(MilkProduction::class);
    }
}
