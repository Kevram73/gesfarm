<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\EggProduction;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EggProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = EggProduction::where('farm_id', $farmId)
            ->with(['livestock', 'createdBy']);
        
        if ($request->has('livestock_id')) {
            $query->where('livestock_id', $request->livestock_id);
        }
        
        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        
        $productions = $query->orderBy('date', 'desc')->paginate(15);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        // Statistiques KPIs
        $today = now()->format('Y-m-d');
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        
        $stats = [
            'today_eggs' => EggProduction::where('farm_id', $farmId)
                ->where('date', $today)
                ->sum('egg_count'),
            'week_eggs' => EggProduction::where('farm_id', $farmId)
                ->where('date', '>=', $thisWeek)
                ->sum('egg_count'),
            'month_eggs' => EggProduction::where('farm_id', $farmId)
                ->where('date', '>=', $thisMonth)
                ->sum('egg_count'),
            'avg_daily' => EggProduction::where('farm_id', $farmId)
                ->where('date', '>=', $thisMonth)
                ->avg('egg_count'),
        ];
        
        return view('poultry.egg-production.index', compact('productions', 'livestock', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        $selectedLivestock = $request->get('livestock_id') 
            ? Livestock::find($request->get('livestock_id'))
            : null;
        
        return view('poultry.egg-production.create', compact('livestock', 'selectedLivestock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'date' => 'required|date',
            'egg_count' => 'required|integer|min:0',
            'egg_weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();

        EggProduction::create($validated);

        return redirect()->route('poultry.egg-production.index')
            ->with('success', 'Production d\'œufs enregistrée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $production = EggProduction::where('farm_id', $farmId)->findOrFail($id);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('poultry.egg-production.edit', compact('production', 'livestock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $production = EggProduction::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'date' => 'required|date',
            'egg_count' => 'required|integer|min:0',
            'egg_weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $production->update($validated);

        return redirect()->route('poultry.egg-production.index')
            ->with('success', 'Production d\'œufs mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $production = EggProduction::where('farm_id', $farmId)->findOrFail($id);
        $production->delete();

        return redirect()->route('poultry.egg-production.index')
            ->with('success', 'Production d\'œufs supprimée avec succès.');
    }
}
