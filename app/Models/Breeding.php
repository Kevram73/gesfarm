<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breeding extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'breedings';

    protected $fillable = [
        'farm_id',
        'male_id',
        'female_id',
        'created_by_id',
        'date',
        'type',
        'success',
        'expected_calving_date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'expected_calving_date' => 'date',
        'success' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function male()
    {
        return $this->belongsTo(Livestock::class, 'male_id');
    }

    public function female()
    {
        return $this->belongsTo(Livestock::class, 'female_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function calving()
    {
        return $this->hasOne(Calving::class);
    }
}
