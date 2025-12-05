<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalNotification extends Model
{
    use HasFactory;

    protected $table = 'internal_notifications';

    protected $fillable = [
        'farm_id',
        'user_id',
        'created_by_id',
        'title',
        'content',
        'level',
        'icon',
        'link',
        'is_read',
        'read_at',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'related_id' => 'integer',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
