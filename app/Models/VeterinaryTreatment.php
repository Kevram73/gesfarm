<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VeterinaryTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_type',
        'treatment_name',
        'description',
        'treatment_date',
        'treatment_time',
        'animal_type',
        'animal_id',
        'animal_identifier',
        'veterinarian_name',
        'veterinarian_license',
        'medications',
        'dosages',
        'cost',
        'next_treatment_date',
        'notes',
        'attachments',
        'user_id',
    ];

    protected $casts = [
        'treatment_date' => 'date',
        'treatment_time' => 'datetime:H:i',
        'medications' => 'array',
        'dosages' => 'array',
        'cost' => 'decimal:2',
        'next_treatment_date' => 'date',
        'attachments' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('treatment_type', $type);
    }

    public function scopeByAnimalType($query, $animalType)
    {
        return $query->where('animal_type', $animalType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('treatment_date', [$startDate, $endDate]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('next_treatment_date', '>=', now())
                    ->where('next_treatment_date', '<=', now()->addDays(7));
    }
}
