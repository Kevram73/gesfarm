<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Inventory::where('farm_id', $farmId)->with('supplier');
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('is_low_stock')) {
            $query->where('is_low_stock', true);
        }
        
        $inventory = $query->orderBy('name')->paginate(15);
        
        return view('equipements.inventaire.index', compact('inventory'));
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
        
        $categories = SelectOption::where('category', 'inventoryCategory')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('equipements.inventaire.create', compact('suppliers', 'categories'));
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
            'category' => 'nullable|string',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'supplier_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['is_low_stock'] = $validated['current_stock'] <= $validated['min_stock'];

        Inventory::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Article d\'inventaire créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $inventory = Inventory::where('farm_id', $farmId)->findOrFail($id);
        
        $suppliers = Supplier::where('farm_id', $farmId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $categories = SelectOption::where('category', 'inventoryCategory')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('equipements.inventaire.edit', compact('inventory', 'suppliers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $inventory = Inventory::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
            'supplier_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['is_low_stock'] = $validated['current_stock'] <= $validated['min_stock'];

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Article d\'inventaire mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $inventory = Inventory::where('farm_id', $farmId)->findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Article d\'inventaire supprimé avec succès.');
    }
}
