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
}
