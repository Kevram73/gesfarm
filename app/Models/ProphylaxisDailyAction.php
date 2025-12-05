<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProphylaxisDailyAction extends Model
{
    use HasFactory;

    protected $table = 'prophylaxis_daily_actions';

    protected $fillable = [
        'prophylaxis_id',
        'completed_by_id',
        'day',
        'date',
        'action',
        'completed',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'day' => 'integer',
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function prophylaxis()
    {
        return $this->belongsTo(Prophylaxis::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }
}
