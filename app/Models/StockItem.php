<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'category_id',
        'unit',
        'current_quantity',
        'minimum_quantity',
        'unit_cost',
        'expiry_date',
        'supplier',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'current_quantity' => 'decimal:2',
        'minimum_quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(StockCategory::class, 'category_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_quantity <= $this->minimum_quantity;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
