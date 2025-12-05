<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chick extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chicks';

    protected $fillable = [
        'farm_id',
        'livestock_id',
        'egg_incubation_id',
        'created_by_id',
        'name',
        'hatch_date',
        'initial_weight',
        'current_weight',
        'age',
        'status',
        'notes',
    ];

    protected $casts = [
        'hatch_date' => 'date',
        'initial_weight' => 'decimal:2',
        'current_weight' => 'decimal:2',
        'age' => 'integer',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function eggIncubation()
    {
        return $this->belongsTo(EggIncubation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
