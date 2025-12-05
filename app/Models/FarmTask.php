<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmTask extends Model
{
    use HasFactory;

    protected $table = 'farm_tasks';

    protected $fillable = [
        'farm_id',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'due_date',
        'assigned_to_id',
        'completed_by_id',
        'created_by_id',
        'employee_id',
        'related_type',
        'related_id',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
