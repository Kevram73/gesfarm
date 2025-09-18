<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'severity',
        'title',
        'message',
        'amount',
        'threshold',
        'is_read',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'threshold' => 'decimal:2',
        'is_read' => 'boolean',
    ];

    /**
     * Scope pour les alertes non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope pour un type d'alerte spécifique
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour un niveau de sévérité spécifique
     */
    public function scopeSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Accessor pour la couleur de la sévérité
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'low' => 'blue',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }

    /**
     * Accessor pour l'icône de la sévérité
     */
    public function getSeverityIconAttribute(): string
    {
        return match($this->severity) {
            'low' => 'info',
            'medium' => 'alert-triangle',
            'high' => 'alert-circle',
            'critical' => 'alert-octagon',
            default => 'bell'
        };
    }

    /**
     * Accessor pour formater le montant
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->amount ? number_format($this->amount, 0, ',', ' ') . ' FCFA' : '';
    }

    /**
     * Accessor pour formater le seuil
     */
    public function getFormattedThresholdAttribute(): string
    {
        return $this->threshold ? number_format($this->threshold, 0, ',', ' ') . ' FCFA' : '';
    }

    /**
     * Accessor pour le temps écoulé
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Méthode pour marquer comme lue
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Méthode pour marquer comme non lue
     */
    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Méthode statique pour créer une alerte de budget dépassé
     */
    public static function createBudgetExceededAlert(Budget $budget): self
    {
        return self::create([
            'type' => 'budget_exceeded',
            'severity' => 'high',
            'title' => 'Budget dépassé',
            'message' => "Le budget '{$budget->name}' a été dépassé. Dépensé: {$budget->formatted_spent} / Budget: {$budget->formatted_amount}",
            'amount' => $budget->spent,
            'threshold' => $budget->amount,
            'is_read' => false
        ]);
    }

    /**
     * Méthode statique pour créer une alerte de flux de trésorerie faible
     */
    public static function createLowCashFlowAlert(float $currentBalance, float $threshold = 100000): self
    {
        return self::create([
            'type' => 'low_cash_flow',
            'severity' => $currentBalance < ($threshold * 0.5) ? 'critical' : 'high',
            'title' => 'Flux de trésorerie faible',
            'message' => "Le solde de trésorerie est faible: " . number_format($currentBalance, 0, ',', ' ') . " FCFA. Seuil d'alerte: " . number_format($threshold, 0, ',', ' ') . " FCFA",
            'amount' => $currentBalance,
            'threshold' => $threshold,
            'is_read' => false
        ]);
    }

    /**
     * Méthode statique pour créer une alerte de dépense inhabituelle
     */
    public static function createUnusualExpenseAlert(FinancialTransaction $transaction, float $averageAmount): self
    {
        return self::create([
            'type' => 'unusual_expense',
            'severity' => 'medium',
            'title' => 'Dépense inhabituelle détectée',
            'message' => "Une dépense de {$transaction->formatted_amount} a été enregistrée pour {$transaction->category}, ce qui est significativement plus élevé que la moyenne ({$averageAmount} FCFA).",
            'amount' => $transaction->amount,
            'threshold' => $averageAmount * 2,
            'is_read' => false
        ]);
    }

    /**
     * Méthode statique pour créer une alerte de revenus manquants
     */
    public static function createMissingIncomeAlert(string $category, int $expectedDays): self
    {
        return self::create([
            'type' => 'missing_income',
            'severity' => 'medium',
            'title' => 'Revenus manquants',
            'message' => "Aucun revenu n'a été enregistré pour '{$category}' depuis {$expectedDays} jours. Vérifiez les ventes en attente.",
            'is_read' => false
        ]);
    }
}
