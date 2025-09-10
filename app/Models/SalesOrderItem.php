<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'product_type',
        'product_name',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'discount_percentage',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    // Calculer le prix total avec remise
    public function calculateTotalPrice(): float
    {
        $subtotal = $this->quantity * $this->unit_price;
        $discount = $subtotal * ($this->discount_percentage / 100);
        return $subtotal - $discount;
    }
}
