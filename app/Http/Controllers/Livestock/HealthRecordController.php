<?php

namespace App\Http\Controllers\Livestock;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HealthRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = HealthRecord::whereHas('livestock', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->with('livestock');
        
        if ($request->has('livestock_id')) {
            $query->where('livestock_id', $request->livestock_id);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        
        $healthRecords = $query->orderBy('date', 'desc')->paginate(15);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('livestock.health-records.index', compact('healthRecords', 'livestock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $selectedLivestock = $request->get('livestock_id') 
            ? Livestock::find($request->get('livestock_id'))
            : null;
        
        return view('livestock.health-records.create', compact('livestock', 'selectedLivestock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'date' => 'required|date',
            'type' => 'required|in:VACCINATION,TREATMENT,CHECKUP,SURGERY,OTHER',
            'description' => 'required|string',
            'veterinarian' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        HealthRecord::create($validated);

        return redirect()->route('livestock.health-records.index')
            ->with('success', 'Fiche sanitaire créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $healthRecord = HealthRecord::whereHas('livestock', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->findOrFail($id);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('livestock.health-records.edit', compact('healthRecord', 'livestock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $healthRecord = HealthRecord::whereHas('livestock', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->findOrFail($id);

        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'date' => 'required|date',
            'type' => 'required|in:VACCINATION,TREATMENT,CHECKUP,SURGERY,OTHER',
            'description' => 'required|string',
            'veterinarian' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $healthRecord->update($validated);

        return redirect()->route('livestock.health-records.index')
            ->with('success', 'Fiche sanitaire mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $healthRecord = HealthRecord::whereHas('livestock', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })->findOrFail($id);
        $healthRecord->delete();

        return redirect()->route('livestock.health-records.index')
            ->with('success', 'Fiche sanitaire supprimée avec succès.');
    }
}
