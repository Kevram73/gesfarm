<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'priority',
        'status',
        'data',
        'read_at',
        'scheduled_at',
        'user_id',
        'related_entity_id',
        'related_entity_type',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedEntity(): MorphTo
    {
        return $this->morphTo('related_entity');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '<=', now());
    }

    // Marquer comme lu
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }
}
