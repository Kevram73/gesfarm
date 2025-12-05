<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\Breeding;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BreedingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Breeding::where('farm_id', $farmId)
            ->with(['male', 'female', 'createdBy', 'calving']);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('success')) {
            $query->where('success', $request->boolean('success'));
        }
        
        $breedings = $query->orderBy('date', 'desc')->paginate(15);
        
        return view('livestock.breedings.index', compact('breedings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farmId = FarmHelper::getFarmId();
        
        $males = Livestock::where('farm_id', $farmId)
            ->where('gender', 'MALE')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $females = Livestock::where('farm_id', $farmId)
            ->where('gender', 'FEMALE')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('livestock.breedings.create', compact('males', 'females'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'male_id' => 'required|exists:livestock,id',
            'female_id' => 'required|exists:livestock,id|different:male_id',
            'date' => 'required|date',
            'type' => 'nullable|in:NATURAL,ARTIFICIAL_INSEMINATION',
            'success' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que le mâle et la femelle sont de types compatibles
        $male = Livestock::findOrFail($validated['male_id']);
        $female = Livestock::findOrFail($validated['female_id']);
        
        if ($male->type !== $female->type) {
            return back()->withErrors([
                'female_id' => 'Le mâle et la femelle doivent être du même type.'
            ])->withInput();
        }

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        $validated['type'] = $validated['type'] ?? 'NATURAL';
        
        // Calculer la date de vêlage attendue (environ 9 mois pour bovins, 5 mois pour ovins)
        $breedingDate = Carbon::parse($validated['date']);
        $gestationDays = $this->getGestationDays($male->type);
        $validated['expected_calving_date'] = $breedingDate->copy()->addDays($gestationDays);

        $breeding = Breeding::create($validated);

        return redirect()->route('livestock.breedings.show', $breeding->id)
            ->with('success', 'Saillie enregistrée. Date de vêlage prévue : ' . $validated['expected_calving_date']->format('d/m/Y'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $breeding = Breeding::where('farm_id', $farmId)
            ->with(['male', 'female', 'createdBy', 'calving'])
            ->findOrFail($id);
        
        // Calculer les jours restants jusqu'au vêlage attendu
        $daysRemaining = null;
        if ($breeding->expected_calving_date && !$breeding->calving) {
            $daysRemaining = Carbon::parse($breeding->expected_calving_date)->diffInDays(now());
        }
        
        return view('livestock.breedings.show', compact('breeding', 'daysRemaining'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $breeding = Breeding::where('farm_id', $farmId)->findOrFail($id);
        
        $males = Livestock::where('farm_id', $farmId)
            ->where('gender', 'MALE')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $females = Livestock::where('farm_id', $farmId)
            ->where('gender', 'FEMALE')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('livestock.breedings.edit', compact('breeding', 'males', 'females'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $breeding = Breeding::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'male_id' => 'required|exists:livestock,id',
            'female_id' => 'required|exists:livestock,id|different:male_id',
            'date' => 'required|date',
            'type' => 'nullable|in:NATURAL,ARTIFICIAL_INSEMINATION',
            'success' => 'nullable|boolean',
            'expected_calving_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $breeding->update($validated);

        return redirect()->route('livestock.breedings.show', $breeding->id)
            ->with('success', 'Saillie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $breeding = Breeding::where('farm_id', $farmId)->findOrFail($id);
        $breeding->delete();

        return redirect()->route('livestock.breedings.index')
            ->with('success', 'Saillie supprimée avec succès.');
    }

    /**
     * Obtenir la durée de gestation en jours selon le type d'animal
     */
    private function getGestationDays(string $type): int
    {
        $type = strtoupper($type);
        
        if (str_contains($type, 'BOVIN') || str_contains($type, 'COW')) {
            return 280; // ~9 mois
        } elseif (str_contains($type, 'OVIN') || str_contains($type, 'SHEEP')) {
            return 150; // ~5 mois
        } elseif (str_contains($type, 'CAPRIN') || str_contains($type, 'GOAT')) {
            return 150; // ~5 mois
        }
        
        return 280; // Par défaut (bovins)
    }
}
