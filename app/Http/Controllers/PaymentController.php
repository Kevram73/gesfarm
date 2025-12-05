<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Harvest;
use App\Models\Customer;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Payment::where('farm_id', $farmId)
            ->with(['harvest', 'customer', 'user']);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        $customers = Customer::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        return view('finances.paiements.index', compact('payments', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $harvests = Harvest::where('farm_id', $farmId)
            ->with(['crop', 'field'])
            ->orderBy('date', 'desc')
            ->get();
            
        $customers = Customer::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        $selectedHarvest = $request->get('harvest_id') ? Harvest::find($request->get('harvest_id')) : null;
        
        return view('finances.paiements.create', compact('harvests', 'customers', 'selectedHarvest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'harvest_id' => 'nullable|exists:harvests,id',
            'customer_id' => 'nullable|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:INCOME,EXPENSE',
            'status' => 'nullable|in:PENDING,COMPLETED,FAILED',
            'method' => 'nullable|in:CASH,CARD,BANK_TRANSFER,MOBILE_MONEY,CHECK',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'PENDING';
        
        if ($validated['status'] === 'COMPLETED' && !isset($validated['paid_at'])) {
            $validated['paid_at'] = now();
        }

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $payment = Payment::where('farm_id', $farmId)->findOrFail($id);
        
        $harvests = Harvest::where('farm_id', $farmId)
            ->with(['crop', 'field'])
            ->orderBy('date', 'desc')
            ->get();
            
        $customers = Customer::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        return view('finances.paiements.edit', compact('payment', 'harvests', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $payment = Payment::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'harvest_id' => 'nullable|exists:harvests,id',
            'customer_id' => 'nullable|exists:customers,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:INCOME,EXPENSE',
            'status' => 'nullable|in:PENDING,COMPLETED,FAILED',
            'method' => 'nullable|in:CASH,CARD,BANK_TRANSFER,MOBILE_MONEY,CHECK',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        // Si le statut passe à COMPLETED et paid_at n'est pas défini
        if ($validated['status'] === 'COMPLETED' && !$payment->paid_at && !isset($validated['paid_at'])) {
            $validated['paid_at'] = now();
        }

        $payment->update($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $payment = Payment::where('farm_id', $farmId)->findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}
