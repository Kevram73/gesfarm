<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prophylaxis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prophylaxis';

    protected $fillable = [
        'farm_id',
        'livestock_id',
        'created_by_id',
        'name',
        'start_date',
        'duration_days',
        'poultry_type',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'duration_days' => 'integer',
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

    public function dailyActions()
    {
        return $this->hasMany(ProphylaxisDailyAction::class);
    }
}
