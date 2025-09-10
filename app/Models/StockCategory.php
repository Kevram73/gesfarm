<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    public function stockItems(): HasMany
    {
        return $this->hasMany(StockItem::class, 'category_id');
    }
}
