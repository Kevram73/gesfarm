<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calving extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'calvings';

    protected $fillable = [
        'farm_id',
        'mother_id',
        'breeding_id',
        'created_by_id',
        'date',
        'type',
        'offspring_count',
        'complications',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'offspring_count' => 'integer',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function mother()
    {
        return $this->belongsTo(Livestock::class, 'mother_id');
    }

    public function breeding()
    {
        return $this->belongsTo(Breeding::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
