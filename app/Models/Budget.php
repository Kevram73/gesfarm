<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'amount',
        'spent',
        'period',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Scope pour les budgets actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour une période spécifique
     */
    public function scopePeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Accessor pour le pourcentage utilisé
     */
    public function getPercentageUsedAttribute(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }
        return ($this->spent / $this->amount) * 100;
    }

    /**
     * Accessor pour le montant restant
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->amount - $this->spent);
    }

    /**
     * Accessor pour le statut de dépassement
     */
    public function getIsOverBudgetAttribute(): bool
    {
        return $this->spent > $this->amount;
    }

    /**
     * Accessor pour la couleur du statut
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->is_over_budget) {
            return 'red';
        } elseif ($this->percentage_used > 90) {
            return 'yellow';
        } elseif ($this->percentage_used > 75) {
            return 'orange';
        } else {
            return 'green';
        }
    }

    /**
     * Accessor pour formater le montant
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accessor pour formater le montant dépensé
     */
    public function getFormattedSpentAttribute(): string
    {
        return number_format($this->spent, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accessor pour formater le montant restant
     */
    public function getFormattedRemainingAttribute(): string
    {
        return number_format($this->remaining_amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Méthode pour mettre à jour le montant dépensé
     */
    public function updateSpentAmount(): void
    {
        $spent = FinancialTransaction::where('type', 'expense')
            ->where('category', $this->category)
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('amount');

        $this->update(['spent' => $spent]);
    }

    /**
     * Méthode pour vérifier si le budget est expiré
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->end_date);
    }

    /**
     * Méthode pour vérifier si le budget est actif
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               Carbon::now()->isBetween($this->start_date, $this->end_date);
    }

    /**
     * Méthode pour archiver le budget
     */
    public function archive(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Méthode pour marquer le budget comme dépassé
     */
    public function markAsOverdue(): void
    {
        $this->update(['status' => 'overdue']);
    }
}