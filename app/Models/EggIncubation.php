<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EggIncubation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'egg_incubations';

    protected $fillable = [
        'farm_id',
        'livestock_id',
        'created_by_id',
        'start_date',
        'poultry_type',
        'breed',
        'egg_count',
        'egg_size',
        'temperature',
        'humidity',
        'incubation_days',
        'expected_hatch_date',
        'actual_hatch_date',
        'hatched_count',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_hatch_date' => 'date',
        'actual_hatch_date' => 'date',
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'egg_count' => 'integer',
        'incubation_days' => 'integer',
        'hatched_count' => 'integer',
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

    public function chicks()
    {
        return $this->hasMany(Chick::class);
    }
}
