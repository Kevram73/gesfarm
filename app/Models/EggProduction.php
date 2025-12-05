<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EggProduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'egg_productions';

    protected $fillable = [
        'farm_id',
        'livestock_id',
        'created_by_id',
        'date',
        'egg_count',
        'egg_weight',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'egg_count' => 'integer',
        'egg_weight' => 'decimal:2',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
