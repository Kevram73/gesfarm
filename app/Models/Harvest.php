<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Harvest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'harvests';

    protected $fillable = [
        'farm_id',
        'field_id',
        'crop_id',
        'customer_id',
        'date',
        'quantity',
        'unit',
        'price_per_unit',
        'total_price',
        'notes',
        'status',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
