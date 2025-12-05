<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipment';

    protected $fillable = [
        'farm_id',
        'supplier_id',
        'name',
        'type',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'status',
        'maintenance_date',
        'next_maintenance',
        'notes',
        'quantity',
        'min_stock',
        'max_stock',
        'unit',
        'is_low_stock',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'max_stock' => 'decimal:2',
        'is_low_stock' => 'boolean',
        'purchase_date' => 'date',
        'maintenance_date' => 'date',
        'next_maintenance' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
