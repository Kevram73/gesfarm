<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory';

    protected $fillable = [
        'farm_id',
        'supplier_id',
        'name',
        'category',
        'current_stock',
        'min_stock',
        'max_stock',
        'unit',
        'unit_price',
        'supplier_name',
        'is_low_stock',
        'notes',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'max_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'is_low_stock' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
