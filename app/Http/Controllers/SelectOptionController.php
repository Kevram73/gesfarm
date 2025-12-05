<?php

namespace App\Http\Controllers;

use App\Models\SelectOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectOptionController extends Controller
{
    // Catégories organisées par sections (comme dans le projet Next.js)
    private const SECTIONS = [
        [
            'title' => 'Général',
            'categories' => [
                ['value' => 'soilType', 'label' => 'Type de sol', 'icon' => '🌱'],
                ['value' => 'climate', 'label' => 'Climat', 'icon' => '🌤️'],
                ['value' => 'fertility', 'label' => 'Fertilité', 'icon' => '🌿'],
                ['value' => 'irrigation', 'label' => 'Irrigation', 'icon' => '💧'],
            ],
        ],
        [
            'title' => 'Cultures',
            'categories' => [
                ['value' => 'cropType', 'label' => 'Type de culture', 'icon' => '🌾'],
                ['value' => 'cropCategory', 'label' => 'Catégorie de culture', 'icon' => '🌽'],
                ['value' => 'harvestStatus', 'label' => 'Statut récolte', 'icon' => '📦'],
            ],
        ],
        [
            'title' => 'Élevage',
            'categories' => [
                ['value' => 'livestockType', 'label' => 'Type de bétail', 'icon' => '🐄'],
                ['value' => 'livestockStatus', 'label' => 'Statut bétail', 'icon' => '📊'],
                ['value' => 'gender', 'label' => 'Sexe', 'icon' => '⚥'],
                ['value' => 'breedingType', 'label' => 'Type de reproduction', 'icon' => '🔬'],
                ['value' => 'calvingType', 'label' => 'Type de vêlage', 'icon' => '🐣'],
                ['value' => 'milkQuality', 'label' => 'Qualité du lait', 'icon' => '🥛'],
            ],
        ],
        [
            'title' => 'Aviculture',
            'categories' => [
                ['value' => 'poultryType', 'label' => 'Type de volaille', 'icon' => '🐔'],
                ['value' => 'incubationStatus', 'label' => 'Statut incubation', 'icon' => '🥚'],
                ['value' => 'prophylaxisStatus', 'label' => 'Statut prophylaxie', 'icon' => '💉'],
                ['value' => 'chickStatus', 'label' => 'Statut poussin', 'icon' => '🐤'],
            ],
        ],
        [
            'title' => 'Ressources',
            'categories' => [
                ['value' => 'equipmentType', 'label' => 'Type d\'équipement', 'icon' => '🔧'],
                ['value' => 'equipmentStatus', 'label' => 'Statut équipement', 'icon' => '⚙️'],
                ['value' => 'inventoryCategory', 'label' => 'Catégorie inventaire', 'icon' => '📋'],
                ['value' => 'taskPriority', 'label' => 'Priorité tâche', 'icon' => '🎯'],
                ['value' => 'taskStatus', 'label' => 'Statut tâche', 'icon' => '✅'],
                ['value' => 'employeeStatus', 'label' => 'Statut employé', 'icon' => '👤'],
            ],
        ],
        [
            'title' => 'Commerce',
            'categories' => [
                ['value' => 'customerType', 'label' => 'Type de client', 'icon' => '👥'],
            ],
        ],
        [
            'title' => 'Analyses & Rapports',
            'categories' => [
                ['value' => 'reportType', 'label' => 'Type de rapport', 'icon' => '📈'],
                ['value' => 'period', 'label' => 'Période', 'icon' => '📅'],
            ],
        ],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = $request->get('farm_id');
        $optionsByCategory = [];

        // Charger toutes les options par catégorie
        $allCategories = collect(self::SECTIONS)->flatMap(fn($section) => $section['categories'])->pluck('value');
        
        foreach ($allCategories as $category) {
            $query = SelectOption::where('category', $category);
            
            if ($farmId) {
                $query->where(function($q) use ($farmId) {
                    $q->where('farm_id', $farmId)->orWhereNull('farm_id');
                });
            } else {
                $query->whereNull('farm_id'); // Options globales par défaut
            }

            $optionsByCategory[$category] = $query->orderBy('order')->get();
        }

        return view('select-options.index', [
            'sections' => self::SECTIONS,
            'optionsByCategory' => $optionsByCategory,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'value' => 'required|string',
            'label' => 'required|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'farm_id' => 'nullable|exists:farms,id',
        ]);

        // Convertir is_active en boolean si c'est une string
        if (isset($validated['is_active']) && is_string($validated['is_active'])) {
            $validated['is_active'] = $validated['is_active'] === '1' || $validated['is_active'] === 'true';
        }

        // Vérifier l'unicité
        $exists = SelectOption::where('category', $validated['category'])
            ->where('value', $validated['value'])
            ->where(function($q) use ($validated) {
                if (isset($validated['farm_id'])) {
                    $q->where('farm_id', $validated['farm_id']);
                } else {
                    $q->whereNull('farm_id');
                }
            })
            ->exists();

        if ($exists) {
            return redirect()->route('select-options.index')
                ->withErrors(['value' => 'Cette valeur existe déjà pour cette catégorie.']);
        }

        SelectOption::create($validated);

        return redirect()->route('select-options.index')
            ->with('success', 'Option créée avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $option = SelectOption::findOrFail($id);

        $validated = $request->validate([
            'value' => 'sometimes|required|string',
            'label' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Convertir is_active en boolean si c'est une string
        if (isset($validated['is_active']) && is_string($validated['is_active'])) {
            $validated['is_active'] = $validated['is_active'] === '1' || $validated['is_active'] === 'true';
        }

        // Vérifier l'unicité si la valeur change
        if (isset($validated['value']) && $validated['value'] !== $option->value) {
            $exists = SelectOption::where('category', $option->category)
                ->where('value', $validated['value'])
                ->where(function($q) use ($option) {
                    $q->where('farm_id', $option->farm_id)
                      ->orWhereNull('farm_id');
                })
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->route('select-options.index')
                    ->withErrors(['value' => 'Cette valeur existe déjà pour cette catégorie.']);
            }
        }

        $option->update($validated);

        return redirect()->route('select-options.index')
            ->with('success', 'Option mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $option = SelectOption::findOrFail($id);
        $option->delete();

        return redirect()->route('select-options.index')
            ->with('success', 'Option supprimée avec succès.');
    }
}
