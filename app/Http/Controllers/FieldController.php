<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farmId = FarmHelper::getFarmId();
        $fields = Field::where('farm_id', $farmId)
            ->withCount(['harvests', 'fieldCrops'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('cultures.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        // Récupérer les options pour les selects
        $soilTypes = SelectOption::where('category', 'soilType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $fertilityOptions = SelectOption::where('category', 'fertility')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $irrigationOptions = SelectOption::where('category', 'irrigation')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('cultures.fields.create', compact('soilTypes', 'fertilityOptions', 'irrigationOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        $farm = FarmHelper::getFarm();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'required|numeric|min:0',
            'soil_type' => 'nullable|string',
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'fertility' => 'nullable|string',
            'irrigation' => 'nullable|string',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Vérifier que la superficie totale des champs ne dépasse pas la superficie de la ferme
        $totalFieldsArea = Field::where('farm_id', $farmId)->sum('area');
        $newTotalArea = $totalFieldsArea + $validated['area'];
        
        if ($farm->total_area && $newTotalArea > $farm->total_area) {
            return back()->withErrors([
                'area' => 'La superficie totale des champs (' . number_format($newTotalArea, 2) . ' ha) dépasse la superficie totale de la ferme (' . number_format($farm->total_area, 2) . ' ha). Superficie disponible : ' . number_format($farm->total_area - $totalFieldsArea, 2) . ' ha.'
            ])->withInput();
        }

        $validated['farm_id'] = $farmId;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['area_used'] = 0; // Initialiser à 0

        Field::create($validated);

        return redirect()->route('fields.index')
            ->with('success', 'Champ créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $field = Field::where('farm_id', $farmId)->findOrFail($id);
        
        // Récupérer les options pour les selects
        $soilTypes = SelectOption::where('category', 'soilType')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $fertilityOptions = SelectOption::where('category', 'fertility')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
            
        $irrigationOptions = SelectOption::where('category', 'irrigation')
            ->where(function($q) use ($farmId) {
                $q->where('farm_id', $farmId)->orWhereNull('farm_id');
            })
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('cultures.fields.edit', compact('field', 'soilTypes', 'fertilityOptions', 'irrigationOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $farm = FarmHelper::getFarm();
        $field = Field::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'required|numeric|min:0',
            'soil_type' => 'nullable|string',
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'fertility' => 'nullable|string',
            'irrigation' => 'nullable|string',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Vérifier que la superficie totale des champs ne dépasse pas la superficie de la ferme
        $totalFieldsArea = Field::where('farm_id', $farmId)->where('id', '!=', $id)->sum('area');
        $newTotalArea = $totalFieldsArea + $validated['area'];
        
        if ($farm->total_area && $newTotalArea > $farm->total_area) {
            return back()->withErrors([
                'area' => 'La superficie totale des champs (' . number_format($newTotalArea, 2) . ' ha) dépasse la superficie totale de la ferme (' . number_format($farm->total_area, 2) . ' ha). Superficie disponible : ' . number_format($farm->total_area - $totalFieldsArea, 2) . ' ha.'
            ])->withInput();
        }

        // Vérifier que la nouvelle superficie n'est pas inférieure à la superficie utilisée
        if ($validated['area'] < $field->area_used) {
            return back()->withErrors([
                'area' => 'La superficie du champ (' . number_format($validated['area'], 2) . ' ha) ne peut pas être inférieure à la superficie utilisée (' . number_format($field->area_used, 2) . ' ha).'
            ])->withInput();
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $field->update($validated);

        return redirect()->route('fields.index')
            ->with('success', 'Champ mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $field = Field::where('farm_id', $farmId)->findOrFail($id);
        $field->delete();

        return redirect()->route('fields.index')
            ->with('success', 'Champ supprimé avec succès.');
    }
}
