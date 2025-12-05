<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\Calving;
use App\Models\Breeding;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalvingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Calving::where('farm_id', $farmId)
            ->with(['mother', 'breeding', 'createdBy']);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        $calvings = $query->orderBy('date', 'desc')->paginate(15);
        
        // Statistiques
        $thisMonth = now()->startOfMonth();
        $stats = [
            'total' => Calving::where('farm_id', $farmId)->count(),
            'this_month' => Calving::where('farm_id', $farmId)
                ->where('date', '>=', $thisMonth)
                ->count(),
            'total_offspring' => Calving::where('farm_id', $farmId)->sum('offspring_count'),
        ];
        
        return view('livestock.calvings.index', compact('calvings', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $mothers = Livestock::where('farm_id', $farmId)
            ->where('gender', 'FEMALE')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $breedings = Breeding::where('farm_id', $farmId)
            ->where('success', true)
            ->whereDoesntHave('calving')
            ->with(['male', 'female'])
            ->orderBy('date', 'desc')
            ->get();
        
        $selectedBreeding = $request->get('breeding_id') 
            ? Breeding::find($request->get('breeding_id'))
            : null;
        
        return view('livestock.calvings.create', compact('mothers', 'breedings', 'selectedBreeding'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'mother_id' => 'required|exists:livestock,id',
            'breeding_id' => 'nullable|exists:breedings,id',
            'date' => 'required|date',
            'type' => 'nullable|in:NORMAL,DIFFICULT,CAESAREAN',
            'offspring_count' => 'required|integer|min:1',
            'complications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        $validated['type'] = $validated['type'] ?? 'NORMAL';

        $calving = Calving::create($validated);
        
        // Marquer la saillie comme ayant abouti à un vêlage
        if ($calving->breeding_id) {
            $calving->breeding->update(['success' => true]);
        }

        return redirect()->route('livestock.calvings.index')
            ->with('success', 'Vêlage enregistré avec succès. ' . $validated['offspring_count'] . ' petit(s) né(s).');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $calving = Calving::where('farm_id', $farmId)->findOrFail($id);
        
        $mothers = Livestock::where('farm_id', $farmId)
            ->where('gender', 'FEMALE')
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $breedings = Breeding::where('farm_id', $farmId)
            ->where('success', true)
            ->with(['male', 'female'])
            ->orderBy('date', 'desc')
            ->get();
        
        return view('livestock.calvings.edit', compact('calving', 'mothers', 'breedings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $calving = Calving::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'mother_id' => 'required|exists:livestock,id',
            'breeding_id' => 'nullable|exists:breedings,id',
            'date' => 'required|date',
            'type' => 'nullable|in:NORMAL,DIFFICULT,CAESAREAN',
            'offspring_count' => 'required|integer|min:1',
            'complications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $calving->update($validated);

        return redirect()->route('livestock.calvings.index')
            ->with('success', 'Vêlage mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $calving = Calving::where('farm_id', $farmId)->findOrFail($id);
        $calving->delete();

        return redirect()->route('livestock.calvings.index')
            ->with('success', 'Vêlage supprimé avec succès.');
    }
}
