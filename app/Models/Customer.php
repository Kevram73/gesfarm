<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'customer_type',
        'tax_number',
        'credit_limit',
        'current_balance',
        'status',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('customer_type', $type);
    }

    public function scopeWithCreditLimit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }
}
