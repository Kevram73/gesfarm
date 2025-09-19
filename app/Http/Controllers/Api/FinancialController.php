<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FinancialController extends Controller
{
    /**
     * @group Gestion Financière
     * 
     * Récupérer toutes les transactions
     */
    public function getTransactions(Request $request): JsonResponse
    {
        $query = Transaction::with('user', 'relatedEntity')
            ->orderBy('transaction_date', 'desc');

        // Filtres
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Créer une nouvelle transaction
     */
    public function createTransaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense,transfer',
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'currency' => 'string|max:3',
            'transaction_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'related_entity_id' => 'nullable|integer',
            'related_entity_type' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction = Transaction::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'currency' => $request->currency ?? 'XOF',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction créée avec succès',
            'data' => $transaction->load('user', 'relatedEntity')
        ], 201);
    }

    /**
     * @group Gestion Financière
     * 
     * Récupérer une transaction spécifique
     */
    public function getTransaction(Transaction $transaction): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $transaction->load('user', 'relatedEntity')
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Mettre à jour une transaction
     */
    public function updateTransaction(Request $request, Transaction $transaction): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|in:income,expense,transfer',
            'category' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:500',
            'amount' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|max:3',
            'transaction_date' => 'sometimes|date',
            'payment_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Transaction mise à jour avec succès',
            'data' => $transaction->load('user', 'relatedEntity')
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Supprimer une transaction
     */
    public function deleteTransaction(Transaction $transaction): JsonResponse
    {
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction supprimée avec succès'
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Récupérer tous les budgets
     */
    public function getBudgets(Request $request): JsonResponse
    {
        $query = Budget::with('user')->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $budgets = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $budgets
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Créer un nouveau budget
     */
    public function createBudget(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
            'currency' => 'string|max:3',
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

        $budget = Budget::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'currency' => $request->currency ?? 'XOF',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Budget créé avec succès',
            'data' => $budget->load('user')
        ], 201);
    }

    /**
     * @group Gestion Financière
     * 
     * Récupérer les rapports financiers
     */
    public function getFinancialReports(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Revenus et dépenses par catégorie
        $incomeByCategory = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $expenseByCategory = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // Total des revenus et dépenses
        $totalIncome = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpense = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Évolution mensuelle
        $monthlyEvolution = Transaction::select(
                DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'summary' => [
                    'total_income' => $totalIncome,
                    'total_expense' => $totalExpense,
                    'net_profit' => $totalIncome - $totalExpense
                ],
                'income_by_category' => $incomeByCategory,
                'expense_by_category' => $expenseByCategory,
                'monthly_evolution' => $monthlyEvolution
            ]
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Récupérer le résumé financier
     */
    public function getSummary(Request $request): JsonResponse
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Définir les dates selon la période
        switch ($period) {
            case 'year':
                $startDate = $startDate ?: now()->startOfYear();
                $endDate = $endDate ?: now()->endOfYear();
                break;
            case 'quarter':
                $startDate = $startDate ?: now()->startOfQuarter();
                $endDate = $endDate ?: now()->endOfQuarter();
                break;
            case 'month':
            default:
                $startDate = $startDate ?: now()->startOfMonth();
                $endDate = $endDate ?: now()->endOfMonth();
                break;
        }

        // Calculs des totaux
        $totalIncome = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpenses = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;
        $profitMargin = $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0;

        // Données mensuelles
        $monthlyIncome = Transaction::where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $monthlyExpenses = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $monthlyProfit = $monthlyIncome - $monthlyExpenses;

        // Données annuelles
        $yearlyIncome = Transaction::where('type', 'income')
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $yearlyExpenses = Transaction::where('type', 'expense')
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $yearlyProfit = $yearlyIncome - $yearlyExpenses;

        // Top catégories de revenus
        $topIncomeCategories = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
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
        $topExpenseCategories = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
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
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $income = Transaction::where('type', 'income')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenses = Transaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
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
     * @group Gestion Financière
     * 
     * Récupérer les alertes financières
     */
    public function getAlerts(): JsonResponse
    {
        // Pour l'instant, retourner des alertes simulées
        // TODO: Implémenter le système d'alertes avec le modèle FinancialAlert
        $alerts = [
            [
                'id' => 1,
                'type' => 'budget_exceeded',
                'severity' => 'high',
                'title' => 'Budget dépassé',
                'message' => 'Le budget pour l\'alimentation a été dépassé de 15%',
                'amount' => 150000,
                'threshold' => 130000,
                'is_read' => false,
                'created_at' => now()->subHours(2)->toISOString()
            ],
            [
                'id' => 2,
                'type' => 'unusual_expense',
                'severity' => 'medium',
                'title' => 'Dépense inhabituelle',
                'message' => 'Une dépense de 50000 FCFA a été enregistrée pour la médecine vétérinaire',
                'amount' => 50000,
                'threshold' => 25000,
                'is_read' => false,
                'created_at' => now()->subHours(5)->toISOString()
            ],
            [
                'id' => 3,
                'type' => 'low_balance',
                'severity' => 'low',
                'title' => 'Solde faible',
                'message' => 'Le solde disponible est inférieur à 100000 FCFA',
                'amount' => 75000,
                'threshold' => 100000,
                'is_read' => true,
                'created_at' => now()->subDays(1)->toISOString()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Marquer une alerte comme lue
     */
    public function markAlertAsRead(int $id): JsonResponse
    {
        // TODO: Implémenter la logique de marquage des alertes
        return response()->json([
            'success' => true,
            'message' => 'Alerte marquée comme lue'
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Récupérer les catégories financières
     */
    public function getCategories(): JsonResponse
    {
        $incomeCategories = Transaction::where('type', 'income')
            ->distinct()
            ->pluck('category')
            ->toArray();

        $expenseCategories = Transaction::where('type', 'expense')
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
     * @group Gestion Financière
     * 
     * Exporter les données financières
     */
    public function exportData(Request $request): JsonResponse
    {
        // TODO: Implémenter l'export des données
        return response()->json([
            'success' => true,
            'message' => 'Export en cours de développement'
        ]);
    }

    /**
     * @group Gestion Financière
     * 
     * Importer les données financières
     */
    public function importData(Request $request): JsonResponse
    {
        // TODO: Implémenter l'import des données
        return response()->json([
            'success' => true,
            'message' => 'Import en cours de développement'
        ]);
    }
}
