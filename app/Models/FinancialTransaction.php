<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'subcategory',
        'amount',
        'description',
        'date',
        'reference',
        'related_entity_type',
        'related_entity_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'related_entity_id' => 'integer',
    ];

    /**
     * Relation avec l'entité liée (optionnel)
     */
    public function relatedEntity(): BelongsTo
    {
        switch ($this->related_entity_type) {
            case 'cattle':
                return $this->belongsTo(Cattle::class, 'related_entity_id');
            case 'poultry':
                return $this->belongsTo(Poultry::class, 'related_entity_id');
            case 'crops':
                return $this->belongsTo(Crop::class, 'related_entity_id');
            case 'stock':
                return $this->belongsTo(StockItem::class, 'related_entity_id');
            case 'veterinary':
                return $this->belongsTo(VeterinaryRecord::class, 'related_entity_id');
            default:
                return $this->belongsTo(Model::class, 'related_entity_id');
        }
    }

    /**
     * Scope pour les revenus
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope pour les dépenses
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour une période donnée
     */
    public function scopePeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Accessor pour formater le montant
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accessor pour la couleur du type
     */
    public function getTypeColorAttribute(): string
    {
        return $this->type === 'income' ? 'green' : 'red';
    }

    /**
     * Accessor pour l'icône du type
     */
    public function getTypeIconAttribute(): string
    {
        return $this->type === 'income' ? 'trending-up' : 'trending-down';
    }
}
