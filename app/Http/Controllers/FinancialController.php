<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\FinancialTransaction;
use App\Models\Budget;
use App\Models\FinancialAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * Récupérer les transactions financières
     */
    public function getTransactions(Request $request): JsonResponse
    {
        $query = FinancialTransaction::query();

        // Filtres
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $transactions = $query->orderBy('date', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'transactions' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'from' => $transactions->firstItem(),
                    'to' => $transactions->lastItem(),
                ]
            ]
        ]);
    }

    /**
     * Créer une nouvelle transaction
     */
    public function createTransaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'related_entity_type' => 'nullable|in:cattle,poultry,crops,stock,veterinary',
            'related_entity_id' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transaction = FinancialTransaction::create($request->all());

            // Générer des alertes si nécessaire
            $this->generateFinancialAlerts($transaction);

            return response()->json([
                'success' => true,
                'message' => 'Transaction créée avec succès',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour une transaction
     */
    public function updateTransaction(Request $request, int $id): JsonResponse
    {
        $transaction = FinancialTransaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|in:income,expense',
            'category' => 'sometimes|string|max:255',
            'subcategory' => 'nullable|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string|max:1000',
            'date' => 'sometimes|date',
            'reference' => 'nullable|string|max:255',
            'related_entity_type' => 'nullable|in:cattle,poultry,crops,stock,veterinary',
            'related_entity_id' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transaction->update($request->all());

            // Régénérer les alertes
            $this->generateFinancialAlerts($transaction);

            return response()->json([
                'success' => true,
                'message' => 'Transaction mise à jour avec succès',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une transaction
     */
    public function deleteTransaction(int $id): JsonResponse
    {
        try {
            $transaction = FinancialTransaction::findOrFail($id);
            $transaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaction supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer le résumé financier
     */
    public function getSummary(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        $period = $request->get('period', 'month');

        // Ajuster les dates selon la période
        switch ($period) {
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'quarter':
                $startDate = Carbon::now()->startOfQuarter();
                $endDate = Carbon::now()->endOfQuarter();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
        }

        // Calculs des totaux
        $totalIncome = FinancialTransaction::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpenses = FinancialTransaction::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;
        $profitMargin = $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0;

        // Données mensuelles
        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $monthlyExpenses = FinancialTransaction::where('type', 'expense')
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $monthlyProfit = $monthlyIncome - $monthlyExpenses;

        // Données annuelles
        $yearlyIncome = FinancialTransaction::where('type', 'income')
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $yearlyExpenses = FinancialTransaction::where('type', 'expense')
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $yearlyProfit = $yearlyIncome - $yearlyExpenses;

        // Top catégories de revenus
        $topIncomeCategories = FinancialTransaction::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($totalIncome) {
                return [
                    'category' => $item->category,
                    'amount' => $item->total,
                    'percentage' => $totalIncome > 0 ? ($item->total / $totalIncome) * 100 : 0
                ];
            });

        // Top catégories de dépenses
        $topExpenseCategories = FinancialTransaction::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($totalExpenses) {
                return [
                    'category' => $item->category,
                    'amount' => $item->total,
                    'percentage' => $totalExpenses > 0 ? ($item->total / $totalExpenses) * 100 : 0
                ];
            });

        // Tendance mensuelle (12 derniers mois)
        $monthlyTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $income = FinancialTransaction::where('type', 'income')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenses = FinancialTransaction::where('type', 'expense')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthlyTrend[] = [
                'month' => $month->format('Y-m'),
                'income' => $income,
                'expenses' => $expenses,
                'profit' => $income - $expenses
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit,
                'profit_margin' => $profitMargin,
                'monthly_income' => $monthlyIncome,
                'monthly_expenses' => $monthlyExpenses,
                'monthly_profit' => $monthlyProfit,
                'yearly_income' => $yearlyIncome,
                'yearly_expenses' => $yearlyExpenses,
                'yearly_profit' => $yearlyProfit,
                'top_income_categories' => $topIncomeCategories,
                'top_expense_categories' => $topExpenseCategories,
                'monthly_trend' => $monthlyTrend
            ]
        ]);
    }

    /**
     * Récupérer les budgets
     */
    public function getBudgets(Request $request): JsonResponse
    {
        $query = Budget::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('period')) {
            $query->where('period', $request->period);
        }

        $perPage = $request->get('per_page', 15);
        $budgets = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'budgets' => $budgets->items(),
                'pagination' => [
                    'current_page' => $budgets->currentPage(),
                    'last_page' => $budgets->lastPage(),
                    'per_page' => $budgets->perPage(),
                    'total' => $budgets->total(),
                ]
            ]
        ]);
    }

    /**
     * Créer un budget
     */
    public function createBudget(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $budget = Budget::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Budget créé avec succès',
                'data' => $budget
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du budget',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les alertes financières
     */
    public function getAlerts(): JsonResponse
    {
        $alerts = FinancialAlert::where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Marquer une alerte comme lue
     */
    public function markAlertAsRead(int $id): JsonResponse
    {
        try {
            $alert = FinancialAlert::findOrFail($id);
            $alert->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Alerte marquée comme lue'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'alerte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les catégories financières
     */
    public function getCategories(): JsonResponse
    {
        $incomeCategories = FinancialTransaction::where('type', 'income')
            ->distinct()
            ->pluck('category')
            ->toArray();

        $expenseCategories = FinancialTransaction::where('type', 'expense')
            ->distinct()
            ->pluck('category')
            ->toArray();

        // Ajouter des catégories par défaut si aucune n'existe
        if (empty($incomeCategories)) {
            $incomeCategories = [
                'Vente de produits',
                'Vente de bétail',
                'Vente de cultures',
                'Subventions',
                'Autres revenus'
            ];
        }

        if (empty($expenseCategories)) {
            $expenseCategories = [
                'Alimentation',
                'Médecine vétérinaire',
                'Maintenance',
                'Équipement',
                'Personnel',
                'Transport',
                'Énergie',
                'Autres dépenses'
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'income_categories' => $incomeCategories,
                'expense_categories' => $expenseCategories
            ]
        ]);
    }

    /**
     * Générer des alertes financières
     */
    private function generateFinancialAlerts(FinancialTransaction $transaction): void
    {
        // Alerte pour dépense inhabituelle
        if ($transaction->type === 'expense') {
            $avgExpense = FinancialTransaction::where('type', 'expense')
                ->where('category', $transaction->category)
                ->where('id', '!=', $transaction->id)
                ->avg('amount');

            if ($avgExpense && $transaction->amount > ($avgExpense * 2)) {
                FinancialAlert::create([
                    'type' => 'unusual_expense',
                    'severity' => 'medium',
                    'title' => 'Dépense inhabituelle détectée',
                    'message' => "Une dépense de {$transaction->amount} FCFA a été enregistrée pour {$transaction->category}, ce qui est significativement plus élevé que la moyenne.",
                    'amount' => $transaction->amount,
                    'threshold' => $avgExpense * 2,
                    'is_read' => false
                ]);
            }
        }

        // Vérifier les budgets dépassés
        $budgets = Budget::where('category', $transaction->category)
            ->where('status', 'active')
            ->where('start_date', '<=', $transaction->date)
            ->where('end_date', '>=', $transaction->date)
            ->get();

        foreach ($budgets as $budget) {
            $spent = FinancialTransaction::where('type', 'expense')
                ->where('category', $budget->category)
                ->whereBetween('date', [$budget->start_date, $budget->end_date])
                ->sum('amount');

            if ($spent > $budget->amount) {
                FinancialAlert::create([
                    'type' => 'budget_exceeded',
                    'severity' => 'high',
                    'title' => 'Budget dépassé',
                    'message' => "Le budget '{$budget->name}' a été dépassé. Dépensé: {$spent} FCFA / Budget: {$budget->amount} FCFA",
                    'amount' => $spent,
                    'threshold' => $budget->amount,
                    'is_read' => false
                ]);
            }
        }
    }
}
