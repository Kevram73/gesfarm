<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelectOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'select_options';

    protected $fillable = [
        'farm_id',
        'category',
        'value',
        'label',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
