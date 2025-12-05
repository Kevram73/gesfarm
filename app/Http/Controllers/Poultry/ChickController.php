<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Chick;
use App\Models\EggIncubation;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChickController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Chick::where('farm_id', $farmId)
            ->with(['livestock', 'eggIncubation', 'createdBy']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('incubation_id')) {
            $query->where('egg_incubation_id', $request->incubation_id);
        }
        
        $chicks = $query->orderBy('hatch_date', 'desc')->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => Chick::where('farm_id', $farmId)->count(),
            'active' => Chick::where('farm_id', $farmId)->where('status', 'ACTIVE')->count(),
            'avg_weight' => Chick::where('farm_id', $farmId)->avg('current_weight'),
        ];
        
        return view('poultry.chicks.index', compact('chicks', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $incubations = EggIncubation::where('farm_id', $farmId)
            ->where('status', 'COMPLETED')
            ->orderBy('actual_hatch_date', 'desc')
            ->get();
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $selectedIncubation = $request->get('incubation_id') 
            ? EggIncubation::find($request->get('incubation_id'))
            : null;
        
        return view('poultry.chicks.create', compact('incubations', 'livestock', 'selectedIncubation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'livestock_id' => 'nullable|exists:livestock,id',
            'egg_incubation_id' => 'nullable|exists:egg_incubations,id',
            'name' => 'nullable|string|max:255',
            'hatch_date' => 'required|date',
            'initial_weight' => 'nullable|numeric|min:0',
            'current_weight' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:ACTIVE,INACTIVE,DECEASED',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'ACTIVE';
        
        // Calculer l'âge en jours
        if (isset($validated['hatch_date'])) {
            $validated['age'] = Carbon::parse($validated['hatch_date'])->diffInDays(now());
        }
        
        // Si initial_weight n'est pas fourni mais current_weight oui, utiliser current_weight
        if (!isset($validated['initial_weight']) && isset($validated['current_weight'])) {
            $validated['initial_weight'] = $validated['current_weight'];
        }

        Chick::create($validated);

        return redirect()->route('poultry.chicks.index')
            ->with('success', 'Poussin enregistré avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $chick = Chick::where('farm_id', $farmId)->findOrFail($id);
        
        $incubations = EggIncubation::where('farm_id', $farmId)
            ->where('status', 'COMPLETED')
            ->orderBy('actual_hatch_date', 'desc')
            ->get();
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('poultry.chicks.edit', compact('chick', 'incubations', 'livestock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $chick = Chick::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'livestock_id' => 'nullable|exists:livestock,id',
            'egg_incubation_id' => 'nullable|exists:egg_incubations,id',
            'name' => 'nullable|string|max:255',
            'hatch_date' => 'required|date',
            'initial_weight' => 'nullable|numeric|min:0',
            'current_weight' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:ACTIVE,INACTIVE,DECEASED',
            'notes' => 'nullable|string',
        ]);

        // Recalculer l'âge
        if (isset($validated['hatch_date'])) {
            $validated['age'] = Carbon::parse($validated['hatch_date'])->diffInDays(now());
        }

        $chick->update($validated);

        return redirect()->route('poultry.chicks.index')
            ->with('success', 'Poussin mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $chick = Chick::where('farm_id', $farmId)->findOrFail($id);
        $chick->delete();

        return redirect()->route('poultry.chicks.index')
            ->with('success', 'Poussin supprimé avec succès.');
    }
}
