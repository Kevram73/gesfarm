<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldCrop extends Model
{
    use HasFactory;

    protected $table = 'field_crops';

    protected $fillable = [
        'field_id',
        'crop_id',
        'planting_date',
        'expected_harvest_date',
        'actual_harvest_date',
        'area',
        'quantity',
        'unit',
        'status',
        'notes',
    ];

    protected $casts = [
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
        'actual_harvest_date' => 'date',
        'area' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
