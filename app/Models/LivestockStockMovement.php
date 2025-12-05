<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockStockMovement extends Model
{
    use HasFactory;

    protected $table = 'livestock_stock_movements';

    protected $fillable = [
        'livestock_id',
        'created_by_id',
        'type',
        'quantity',
        'reason',
        'notes',
        'date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'date' => 'datetime',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
