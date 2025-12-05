<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class CropController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farmId = FarmHelper::getFarmId();
        $crops = Crop::where('farm_id', $farmId)
            ->withCount(['harvests', 'fieldCrops'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('cultures.crops.index', compact('crops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        // Récupérer les options pour les selects
        $cropTypes = SelectOption::where('category', 'cropType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $cropCategories = SelectOption::where('category', 'cropCategory')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('cultures.crops.create', compact('cropTypes', 'cropCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'variety' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'planting_season' => 'nullable|string',
            'harvest_season' => 'nullable|string',
            'growth_period' => 'nullable|integer|min:0',
            'water_needs' => 'nullable|string',
            'soil_requirements' => 'nullable|string',
            'price_per_unit' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Crop::create($validated);

        return redirect()->route('crops.index')
            ->with('success', 'Culture créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $crop = Crop::where('farm_id', $farmId)->findOrFail($id);
        
        // Récupérer les options pour les selects
        $cropTypes = SelectOption::where('category', 'cropType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $cropCategories = SelectOption::where('category', 'cropCategory')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('cultures.crops.edit', compact('crop', 'cropTypes', 'cropCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $crop = Crop::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'variety' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            'planting_season' => 'nullable|string',
            'harvest_season' => 'nullable|string',
            'growth_period' => 'nullable|integer|min:0',
            'water_needs' => 'nullable|string',
            'soil_requirements' => 'nullable|string',
            'price_per_unit' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $crop->update($validated);

        return redirect()->route('crops.index')
            ->with('success', 'Culture mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $crop = Crop::where('farm_id', $farmId)->findOrFail($id);
        $crop->delete();

        return redirect()->route('crops.index')
            ->with('success', 'Culture supprimée avec succès.');
    }
}
