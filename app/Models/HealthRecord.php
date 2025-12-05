<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $table = 'health_records';

    protected $fillable = [
        'livestock_id',
        'date',
        'type',
        'description',
        'veterinarian',
        'cost',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }
}
