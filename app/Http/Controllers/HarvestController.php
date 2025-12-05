<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\Crop;
use App\Models\Field;
use App\Models\Customer;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HarvestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farmId = FarmHelper::getFarmId();
        $harvests = Harvest::where('farm_id', $farmId)
            ->with(['crop', 'field', 'customer', 'createdBy'])
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('cultures.harvests.index', compact('harvests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        $crops = Crop::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        $fields = Field::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        $harvestStatuses = SelectOption::where('category', 'harvestStatus')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('cultures.harvests.create', compact('crops', 'fields', 'customers', 'harvestStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'field_id' => 'nullable|exists:fields,id',
            'crop_id' => 'required|exists:crops,id',
            'customer_id' => 'nullable|exists:customers,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'price_per_unit' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        
        // Calculer le prix total si non fourni
        if (!isset($validated['total_price']) && isset($validated['price_per_unit']) && isset($validated['quantity'])) {
            $validated['total_price'] = $validated['price_per_unit'] * $validated['quantity'];
        }

        Harvest::create($validated);

        return redirect()->route('harvests.index')
            ->with('success', 'Récolte créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $harvest = Harvest::where('farm_id', $farmId)->findOrFail($id);
        
        $crops = Crop::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        $fields = Field::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        $harvestStatuses = SelectOption::where('category', 'harvestStatus')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('cultures.harvests.edit', compact('harvest', 'crops', 'fields', 'customers', 'harvestStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $harvest = Harvest::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'field_id' => 'nullable|exists:fields,id',
            'crop_id' => 'required|exists:crops,id',
            'customer_id' => 'nullable|exists:customers,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'price_per_unit' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $validated['updated_by_id'] = Auth::id();
        
        // Calculer le prix total si non fourni
        if (!isset($validated['total_price']) && isset($validated['price_per_unit']) && isset($validated['quantity'])) {
            $validated['total_price'] = $validated['price_per_unit'] * $validated['quantity'];
        }

        $harvest->update($validated);

        return redirect()->route('harvests.index')
            ->with('success', 'Récolte mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $harvest = Harvest::where('farm_id', $farmId)->findOrFail($id);
        $harvest->delete();

        return redirect()->route('harvests.index')
            ->with('success', 'Récolte supprimée avec succès.');
    }
}
