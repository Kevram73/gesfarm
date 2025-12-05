<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Country;
use App\Models\User;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display the farm information.
     */
    public function index()
    {
        $farm = FarmHelper::getFarm();
        $farm->load(['country', 'manager']);
        return view('farm.index', compact('farm'));
    }

    /**
     * Show the form for editing the farm.
     */
    public function edit()
    {
        $farm = FarmHelper::getFarm();
        $farmId = $farm->id;
        $countries = Country::all();
        $managers = User::whereIn('role', ['SUPER_ADMIN', 'ADMIN', 'OWNER'])->get();
        
        // Récupérer les options de type de sol et climat
        $soilTypes = SelectOption::where('category', 'soilType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $climates = SelectOption::where('category', 'climate')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('farm.edit', compact('farm', 'countries', 'managers', 'soilTypes', 'climates'));
    }

    /**
     * Update the farm information.
     */
    public function update(Request $request)
    {
        $farm = FarmHelper::getFarm();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'country_id' => 'nullable|exists:countries,id',
            'city' => 'nullable|string',
            'code' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'total_area' => 'nullable|numeric|min:0',
            'cultivated_area' => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|string',
            'soil_type_other' => 'nullable|string',
            'climate' => 'nullable|string',
            'climate_other' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Si "Autre" est sélectionné, utiliser la valeur du champ "other"
        if ($validated['soil_type'] === 'other' && isset($validated['soil_type_other'])) {
            $validated['soil_type'] = $validated['soil_type_other'];
        }
        unset($validated['soil_type_other']);

        if ($validated['climate'] === 'other' && isset($validated['climate_other'])) {
            $validated['climate'] = $validated['climate_other'];
        }
        unset($validated['climate_other']);

        $farm->update($validated);
        
        // Réinitialiser le cache après mise à jour
        FarmHelper::clearCache();

        return redirect()->route('farm.index')
            ->with('success', 'Informations de la ferme mises à jour avec succès.');
    }
}
