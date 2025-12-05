<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\Prophylaxis;
use App\Models\ProphylaxisDailyAction;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProphylaxisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = Prophylaxis::where('farm_id', $farmId)
            ->with(['livestock', 'createdBy', 'dailyActions']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $prophylaxis = $query->orderBy('start_date', 'desc')->paginate(15);
        
        return view('poultry.prophylaxis.index', compact('prophylaxis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        
        return view('poultry.prophylaxis.create', compact('livestock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer|min:1',
            'poultry_type' => 'nullable|string',
            'description' => 'nullable|string',
            'daily_actions' => 'required|array|min:1',
            'daily_actions.*.day' => 'required|integer|min:1',
            'daily_actions.*.action' => 'required|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();
        $validated['status'] = 'IN_PROGRESS';
        
        $dailyActions = $validated['daily_actions'];
        unset($validated['daily_actions']);

        $prophylaxis = Prophylaxis::create($validated);
        
        // Créer les actions quotidiennes
        $startDate = Carbon::parse($validated['start_date']);
        foreach ($dailyActions as $action) {
            $actionDate = $startDate->copy()->addDays($action['day'] - 1);
            
            ProphylaxisDailyAction::create([
                'prophylaxis_id' => $prophylaxis->id,
                'day' => $action['day'],
                'date' => $actionDate,
                'action' => $action['action'],
                'completed' => false,
            ]);
        }

        return redirect()->route('poultry.prophylaxis.show', $prophylaxis->id)
            ->with('success', 'Prophylaxie créée avec succès. ' . count($dailyActions) . ' actions quotidiennes programmées.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $prophylaxis = Prophylaxis::where('farm_id', $farmId)
            ->with(['livestock', 'createdBy', 'dailyActions.completedBy'])
            ->findOrFail($id);
        
        // Calculer la progression
        $daysElapsed = Carbon::parse($prophylaxis->start_date)->diffInDays(now());
        $progress = min(100, ($daysElapsed / $prophylaxis->duration_days) * 100);
        
        // Actions complétées
        $completedActions = $prophylaxis->dailyActions()->where('completed', true)->count();
        $totalActions = $prophylaxis->dailyActions()->count();
        
        return view('poultry.prophylaxis.show', compact('prophylaxis', 'progress', 'completedActions', 'totalActions'));
    }

    /**
     * Marquer une action quotidienne comme complétée
     */
    public function completeAction(Request $request, string $id, string $actionId)
    {
        $farmId = FarmHelper::getFarmId();
        $prophylaxis = Prophylaxis::where('farm_id', $farmId)->findOrFail($id);
        
        $action = $prophylaxis->dailyActions()->findOrFail($actionId);
        
        $action->update([
            'completed' => true,
            'completed_by_id' => Auth::id(),
            'completed_at' => now(),
            'notes' => $request->input('notes'),
        ]);
        
        // Vérifier si toutes les actions sont complétées
        $allCompleted = $prophylaxis->dailyActions()->where('completed', false)->count() === 0;
        if ($allCompleted) {
            $prophylaxis->update(['status' => 'COMPLETED']);
        }
        
        return back()->with('success', 'Action marquée comme complétée.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $prophylaxis = Prophylaxis::where('farm_id', $farmId)
            ->with('dailyActions')
            ->findOrFail($id);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('poultry.prophylaxis.edit', compact('prophylaxis', 'livestock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $prophylaxis = Prophylaxis::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer|min:1',
            'poultry_type' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:IN_PROGRESS,COMPLETED,CANCELLED',
        ]);

        $prophylaxis->update($validated);

        return redirect()->route('poultry.prophylaxis.show', $prophylaxis->id)
            ->with('success', 'Prophylaxie mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $prophylaxis = Prophylaxis::where('farm_id', $farmId)->findOrFail($id);
        $prophylaxis->delete();

        return redirect()->route('poultry.prophylaxis.index')
            ->with('success', 'Prophylaxie supprimée avec succès.');
    }
}
