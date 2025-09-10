<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_item_id',
        'type',
        'quantity',
        'unit_cost',
        'reason',
        'user_id',
        'movement_date',
        'notes',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
