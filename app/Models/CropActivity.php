<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'activity_type',
        'activity_date',
        'description',
        'materials_used',
        'cost',
        'notes',
        'performed_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'materials_used' => 'array',
        'cost' => 'decimal:2',
    ];

    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
