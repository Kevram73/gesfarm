<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\EggIncubation;
use App\Models\Livestock;
use App\Models\IncubationRecommendation;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EggIncubationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = EggIncubation::where('farm_id', $farmId)
            ->with(['livestock', 'createdBy', 'chicks']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('poultry_type')) {
            $query->where('poultry_type', $request->poultry_type);
        }
        
        $incubations = $query->orderBy('start_date', 'desc')->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => EggIncubation::where('farm_id', $farmId)->count(),
            'active' => EggIncubation::where('farm_id', $farmId)->where('status', 'IN_PROGRESS')->count(),
            'completed' => EggIncubation::where('farm_id', $farmId)->where('status', 'COMPLETED')->count(),
            'total_hatched' => EggIncubation::where('farm_id', $farmId)->sum('hatched_count'),
        ];
        
        return view('poultry.incubations.index', compact('incubations', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where('type', 'LIKE', '%POULTRY%')
            ->orWhere('type', 'LIKE', '%VOLAILLE%')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $recommendations = IncubationRecommendation::where('is_active', true)
            ->orderBy('poultry_type')
            ->get();
        
        return view('poultry.incubations.create', compact('livestock', 'recommendations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'livestock_id' => 'nullable|exists:livestock,id',
            'start_date' => 'required|date',
            'poultry_type' => 'required|string',
            'breed' => 'nullable|string',
            'egg_count' => 'required|integer|min:1',
            'egg_size' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:50',
            'humidity' => 'nullable|numeric|min:0|max:100',
            'incubation_days' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Récupérer les recommandations si les paramètres ne sont pas fournis
        $recommendation = null;
        if (!$validated['temperature'] || !$validated['humidity'] || !$validated['incubation_days']) {
            $recommendation = IncubationRecommendation::where('poultry_type', strtoupper($validated['poultry_type']))
                ->where('is_active', true)
                ->first();
        }

        // Utiliser les recommandations ou les valeurs par défaut
        $temperature = $validated['temperature'] ?? $recommendation?->temperature ?? 37.5;
        $humidity = $validated['humidity'] ?? $recommendation?->humidity ?? 55;
        $incubationDays = $validated['incubation_days'] ?? $recommendation?->incubation_days ?? 21;
        
        // Calculer la date d'éclosion attendue
        $startDate = Carbon::parse($validated['start_date']);
        $expectedHatchDate = $startDate->copy()->addDays($incubationDays);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        $validated['temperature'] = $temperature;
        $validated['humidity'] = $humidity;
        $validated['incubation_days'] = $incubationDays;
        $validated['expected_hatch_date'] = $expectedHatchDate;
        $validated['status'] = 'IN_PROGRESS';
        $validated['hatched_count'] = 0;

        $incubation = EggIncubation::create($validated);

        return redirect()->route('poultry.incubations.show', $incubation->id)
            ->with('success', 'Incubation créée avec succès. Date d\'éclosion prévue : ' . $expectedHatchDate->format('d/m/Y'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $incubation = EggIncubation::where('farm_id', $farmId)
            ->with(['livestock', 'createdBy', 'chicks'])
            ->findOrFail($id);
        
        // Calculer le pourcentage de progression
        $daysElapsed = Carbon::parse($incubation->start_date)->diffInDays(now());
        $progress = min(100, ($daysElapsed / $incubation->incubation_days) * 100);
        
        // Calculer le taux d'éclosion si complété
        $hatchRate = $incubation->egg_count > 0 
            ? ($incubation->hatched_count / $incubation->egg_count) * 100 
            : 0;
        
        return view('poultry.incubations.show', compact('incubation', 'progress', 'hatchRate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $incubation = EggIncubation::where('farm_id', $farmId)->findOrFail($id);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $recommendations = IncubationRecommendation::where('is_active', true)
            ->orderBy('poultry_type')
            ->get();
        
        return view('poultry.incubations.edit', compact('incubation', 'livestock', 'recommendations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $incubation = EggIncubation::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'livestock_id' => 'nullable|exists:livestock,id',
            'start_date' => 'required|date',
            'poultry_type' => 'required|string',
            'breed' => 'nullable|string',
            'egg_count' => 'required|integer|min:1',
            'egg_size' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:50',
            'humidity' => 'nullable|numeric|min:0|max:100',
            'incubation_days' => 'nullable|integer|min:1',
            'expected_hatch_date' => 'nullable|date',
            'actual_hatch_date' => 'nullable|date',
            'hatched_count' => 'nullable|integer|min:0',
            'status' => 'nullable|in:IN_PROGRESS,COMPLETED,FAILED',
            'notes' => 'nullable|string',
        ]);

        // Recalculer la date d'éclosion si nécessaire
        if (isset($validated['start_date']) && isset($validated['incubation_days'])) {
            $startDate = Carbon::parse($validated['start_date']);
            $validated['expected_hatch_date'] = $startDate->copy()->addDays($validated['incubation_days']);
        }

        $incubation->update($validated);

        return redirect()->route('poultry.incubations.show', $incubation->id)
            ->with('success', 'Incubation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $incubation = EggIncubation::where('farm_id', $farmId)->findOrFail($id);
        $incubation->delete();

        return redirect()->route('poultry.incubations.index')
            ->with('success', 'Incubation supprimée avec succès.');
    }
}
