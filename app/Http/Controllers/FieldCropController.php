<?php

namespace App\Http\Controllers;

use App\Models\FieldCrop;
use App\Models\Field;
use App\Models\Crop;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;

class FieldCropController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        $fieldId = $request->get('field_id');
        
        $query = FieldCrop::with(['field', 'crop'])
            ->whereHas('field', function($q) use ($farmId) {
                $q->where('farm_id', $farmId);
            });
            
        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }
        
        $fieldCrops = $query->orderBy('planting_date', 'desc')->paginate(15);
        $fields = Field::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        return view('cultures.field-crops.index', compact('fieldCrops', 'fields', 'fieldId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        $fieldId = $request->get('field_id');
        
        // Récupérer les champs disponibles (avec espace disponible)
        $fields = Field::where('farm_id', $farmId)
            ->where('is_active', true)
            ->get()
            ->filter(function($field) {
                return $field->hasAvailableArea();
            });
            
        $crops = Crop::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        $selectedField = $fieldId ? Field::where('farm_id', $farmId)->find($fieldId) : null;
        
        return view('cultures.field-crops.create', compact('fields', 'crops', 'selectedField'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'crop_id' => 'required|exists:crops,id',
            'planting_date' => 'required|date|after_or_equal:today',
            'expected_harvest_date' => 'nullable|date|after:planting_date',
            'area' => 'required|numeric|min:0.01',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:PLANTED,GROWING,READY,HARVESTED,FAILED',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que le champ appartient à la ferme
        $field = Field::where('farm_id', $farmId)->findOrFail($validated['field_id']);
        
        // Vérifier que le champ a assez d'espace disponible
        if (!$field->hasAvailableArea($validated['area'])) {
            return back()->withErrors([
                'area' => 'Le champ "' . $field->name . '" n\'a pas assez d\'espace disponible. Superficie disponible : ' . number_format($field->available_area, 2) . ' ha. Superficie demandée : ' . number_format($validated['area'], 2) . ' ha.'
            ])->withInput();
        }

        // Vérifier qu'il n'y a pas de chevauchement de dates avec d'autres cultures dans le même champ
        $overlappingCrops = FieldCrop::where('field_id', $field->id)
            ->where('status', '!=', 'HARVESTED')
            ->where('status', '!=', 'FAILED')
            ->where(function($q) use ($validated) {
                $q->whereBetween('planting_date', [
                    $validated['planting_date'],
                    $validated['expected_harvest_date'] ?? date('Y-m-d', strtotime('+1 year'))
                ])
                ->orWhereBetween('expected_harvest_date', [
                    $validated['planting_date'],
                    $validated['expected_harvest_date'] ?? date('Y-m-d', strtotime('+1 year'))
                ]);
            })
            ->exists();
            
        if ($overlappingCrops) {
            return back()->withErrors([
                'planting_date' => 'Il y a déjà une culture planifiée dans ce champ pour cette période. Veuillez choisir une autre date ou un autre champ.'
            ])->withInput();
        }

        $validated['status'] = $validated['status'] ?? 'PLANTED';

        $fieldCrop = FieldCrop::create($validated);
        
        // Mettre à jour la superficie utilisée du champ
        $field->area_used += $validated['area'];
        $field->save();

        return redirect()->route('field-crops.index', ['field_id' => $field->id])
            ->with('success', 'Culture planifiée avec succès dans le champ.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $fieldCrop = FieldCrop::whereHas('field', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->findOrFail($id);
        
        $fields = Field::where('farm_id', $farmId)
            ->where('is_active', true)
            ->get()
            ->filter(function($field) use ($fieldCrop) {
                // Permettre le champ actuel même s'il n'a plus d'espace (car on modifie une culture existante)
                return $field->id == $fieldCrop->field_id || $field->hasAvailableArea();
            });
            
        $crops = Crop::where('farm_id', $farmId)->where('is_active', true)->orderBy('name')->get();
        
        return view('cultures.field-crops.edit', compact('fieldCrop', 'fields', 'crops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $fieldCrop = FieldCrop::whereHas('field', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->findOrFail($id);

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'crop_id' => 'required|exists:crops,id',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'nullable|date|after:planting_date',
            'actual_harvest_date' => 'nullable|date|after_or_equal:planting_date',
            'area' => 'required|numeric|min:0.01',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:PLANTED,GROWING,READY,HARVESTED,FAILED',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que le champ appartient à la ferme
        $field = Field::where('farm_id', $farmId)->findOrFail($validated['field_id']);
        $oldField = $fieldCrop->field;
        
        // Gérer le changement de superficie utilisée
        $areaDifference = $validated['area'] - $fieldCrop->area;
        
        // Si le champ change
        if ($fieldCrop->field_id != $validated['field_id']) {
            // Libérer l'espace dans l'ancien champ
            $oldField->area_used -= $fieldCrop->area;
            $oldField->save();
            
            // Vérifier que le nouveau champ a assez d'espace
            if (!$field->hasAvailableArea($validated['area'])) {
                return back()->withErrors([
                    'area' => 'Le champ "' . $field->name . '" n\'a pas assez d\'espace disponible. Superficie disponible : ' . number_format($field->available_area, 2) . ' ha.'
                ])->withInput();
            }
            
            // Utiliser l'espace dans le nouveau champ
            $field->area_used += $validated['area'];
            $field->save();
        } else {
            // Même champ, ajuster la superficie utilisée
            $newAreaUsed = $oldField->area_used + $areaDifference;
            
            if ($newAreaUsed > $oldField->area) {
                return back()->withErrors([
                    'area' => 'La superficie utilisée (' . number_format($newAreaUsed, 2) . ' ha) dépasse la superficie du champ (' . number_format($oldField->area, 2) . ' ha).'
                ])->withInput();
            }
            
            $oldField->area_used = $newAreaUsed;
            $oldField->save();
        }

        $fieldCrop->update($validated);

        return redirect()->route('field-crops.index', ['field_id' => $field->id])
            ->with('success', 'Culture mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $fieldCrop = FieldCrop::whereHas('field', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->findOrFail($id);
        
        $field = $fieldCrop->field;
        
        // Libérer l'espace dans le champ
        $field->area_used = max(0, $field->area_used - $fieldCrop->area);
        $field->save();
        
        $fieldCrop->delete();

        return redirect()->route('field-crops.index', ['field_id' => $field->id])
            ->with('success', 'Culture supprimée avec succès.');
    }
}
