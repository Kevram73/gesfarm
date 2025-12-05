<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'farms';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'country_id',
        'city',
        'is_active',
        'code',
        'manager_id',
        'settings',
        'total_area',
        'cultivated_area',
        'soil_type',
        'climate',
    ];

    protected $casts = [
        'settings' => 'array',
        'total_area' => 'decimal:2',
        'cultivated_area' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relations
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function livestock()
    {
        return $this->hasMany(Livestock::class);
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function tasks()
    {
        return $this->hasMany(FarmTask::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function selectOptions()
    {
        return $this->hasMany(SelectOption::class);
    }

    public function notifications()
    {
        return $this->hasMany(InternalNotification::class);
    }

    // Relations avicoles
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

    // Relations généalogie
    public function breedings()
    {
        return $this->hasMany(Breeding::class);
    }

    public function calvings()
    {
        return $this->hasMany(Calving::class);
    }

    public function milkProductions()
    {
        return $this->hasMany(MilkProduction::class);
    }
}
