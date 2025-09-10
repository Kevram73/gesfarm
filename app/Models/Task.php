<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'priority',
        'status',
        'due_date',
        'completed_date',
        'assigned_to',
        'created_by',
        'zone_id',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'completed' && $this->due_date->isPast();
    }
}
