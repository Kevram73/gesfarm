<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Supplier;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Equipment::where('farm_id', $farmId)->with('supplier');
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('is_low_stock')) {
            $query->where('is_low_stock', true);
        }
        
        $equipment = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('equipements.equipements.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        $suppliers = Supplier::where('farm_id', $farmId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $equipmentTypes = SelectOption::where('category', 'equipmentType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('equipements.equipements.create', compact('suppliers', 'equipmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:ACTIVE,INACTIVE,MAINTENANCE,REPAIR',
            'maintenance_date' => 'nullable|date',
            'next_maintenance' => 'nullable|date|after:maintenance_date',
            'quantity' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['is_low_stock'] = false;
        
        // Vérifier le stock bas si quantity est fourni
        if (isset($validated['quantity']) && isset($validated['min_stock'])) {
            $validated['is_low_stock'] = $validated['quantity'] <= $validated['min_stock'];
        }

        Equipment::create($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Équipement créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $equipment = Equipment::where('farm_id', $farmId)->findOrFail($id);
        
        $suppliers = Supplier::where('farm_id', $farmId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $equipmentTypes = SelectOption::where('category', 'equipmentType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('equipements.equipements.edit', compact('equipment', 'suppliers', 'equipmentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $equipment = Equipment::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:ACTIVE,INACTIVE,MAINTENANCE,REPAIR',
            'maintenance_date' => 'nullable|date',
            'next_maintenance' => 'nullable|date|after:maintenance_date',
            'quantity' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // Vérifier le stock bas si quantity est fourni
        if (isset($validated['quantity']) && isset($validated['min_stock'])) {
            $validated['is_low_stock'] = $validated['quantity'] <= $validated['min_stock'];
        } else {
            $validated['is_low_stock'] = false;
        }

        $equipment->update($validated);

        return redirect()->route('equipment.index')
            ->with('success', 'Équipement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $equipment = Equipment::where('farm_id', $farmId)->findOrFail($id);
        $equipment->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Équipement supprimé avec succès.');
    }
}
