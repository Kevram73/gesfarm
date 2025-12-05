<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = \App\Helpers\FarmHelper::getFarmId();
        
        $query = Customer::where('farm_id', $farmId);

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $customers = $query->paginate(15);

        return view('personnel.clients.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personnel.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = \App\Helpers\FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'type' => 'nullable|in:INDIVIDUAL,BUSINESS,COOPERATIVE',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmId = \App\Helpers\FarmHelper::getFarmId();
        $customer = Customer::where('farm_id', $farmId)
            ->with(['harvests', 'payments'])
            ->findOrFail($id);
        return view('personnel.clients.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = \App\Helpers\FarmHelper::getFarmId();
        $customer = Customer::where('farm_id', $farmId)->findOrFail($id);
        return view('personnel.clients.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = \App\Helpers\FarmHelper::getFarmId();
        $customer = Customer::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'type' => 'nullable|in:INDIVIDUAL,BUSINESS,COOPERATIVE',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = \App\Helpers\FarmHelper::getFarmId();
        $customer = Customer::where('farm_id', $farmId)->findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
