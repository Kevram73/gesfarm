<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoultryFeedRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'poultry_feed_records';

    protected $fillable = [
        'farm_id',
        'livestock_id',
        'created_by_id',
        'date',
        'feed_type',
        'quantity_grams',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity_grams' => 'decimal:2',
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
}
