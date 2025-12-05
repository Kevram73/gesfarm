<?php

namespace App\Http\Controllers;

use App\Models\Livestock;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class LivestockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Livestock::where('farm_id', $farmId);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('is_low_stock')) {
            $query->where('is_low_stock', true);
        }
        
        $livestock = $query->with('parent')->orderBy('created_at', 'desc')->paginate(15);
        
        $livestockTypes = SelectOption::where('category', 'livestockType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('betail.livestock.index', compact('livestock', 'livestockTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        $livestockTypes = SelectOption::where('category', 'livestockType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $breeds = SelectOption::where('category', 'breed')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        $parents = Livestock::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('betail.livestock.create', compact('livestockTypes', 'breeds', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:livestock,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'breed' => 'nullable|string',
            'age' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'nullable|in:MALE,FEMALE',
            'status' => 'nullable|in:ACTIVE,INACTIVE,SOLD,DECEASED',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['is_low_stock'] = false;
        
        // Vérifier le stock bas si quantity est fourni
        if (isset($validated['quantity']) && isset($validated['min_stock'])) {
            $validated['is_low_stock'] = $validated['quantity'] <= $validated['min_stock'];
        }

        Livestock::create($validated);

        return redirect()->route('livestock.index')
            ->with('success', 'Bétail créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $livestock = Livestock::where('farm_id', $farmId)->findOrFail($id);
        
        $livestockTypes = SelectOption::where('category', 'livestockType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $breeds = SelectOption::where('category', 'breed')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        $parents = Livestock::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();
        
        return view('betail.livestock.edit', compact('livestock', 'livestockTypes', 'breeds', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $livestock = Livestock::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:livestock,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'breed' => 'nullable|string',
            'age' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'nullable|in:MALE,FEMALE',
            'status' => 'nullable|in:ACTIVE,INACTIVE,SOLD,DECEASED',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Vérifier le stock bas si quantity est fourni
        if (isset($validated['quantity']) && isset($validated['min_stock'])) {
            $validated['is_low_stock'] = $validated['quantity'] <= $validated['min_stock'];
        } else {
            $validated['is_low_stock'] = false;
        }

        $livestock->update($validated);

        return redirect()->route('livestock.index')
            ->with('success', 'Bétail mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $livestock = Livestock::where('farm_id', $farmId)->findOrFail($id);
        $livestock->delete();

        return redirect()->route('livestock.index')
            ->with('success', 'Bétail supprimé avec succès.');
    }
}
