<?php

namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use App\Models\PoultryFeedRecord;
use App\Models\Livestock;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PoultryFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmId = FarmHelper::getFarmId();
        
        $query = PoultryFeedRecord::where('farm_id', $farmId)
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
        
        $feedRecords = $query->orderBy('date', 'desc')->paginate(15);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        // Statistiques
        $today = now()->format('Y-m-d');
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        
        $stats = [
            'today_total' => PoultryFeedRecord::where('farm_id', $farmId)
                ->where('date', $today)
                ->sum('quantity_grams'),
            'week_total' => PoultryFeedRecord::where('farm_id', $farmId)
                ->where('date', '>=', $thisWeek)
                ->sum('quantity_grams'),
            'month_total' => PoultryFeedRecord::where('farm_id', $farmId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('quantity_grams'),
        ];
        
        return view('poultry.feed.index', compact('feedRecords', 'livestock', 'stats'));
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
        
        return view('poultry.feed.create', compact('livestock', 'selectedLivestock'));
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
            'feed_type' => 'required|string|max:255',
            'quantity_grams' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['farm_id'] = $farmId;
        $validated['created_by_id'] = Auth::id();

        PoultryFeedRecord::create($validated);

        return redirect()->route('poultry.feed.index')
            ->with('success', 'Enregistrement d\'alimentation créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $feedRecord = PoultryFeedRecord::where('farm_id', $farmId)->findOrFail($id);
        
        $livestock = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
        
        return view('poultry.feed.edit', compact('feedRecord', 'livestock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $feedRecord = PoultryFeedRecord::where('farm_id', $farmId)->findOrFail($id);

        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'date' => 'required|date',
            'feed_type' => 'required|string|max:255',
            'quantity_grams' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $feedRecord->update($validated);

        return redirect()->route('poultry.feed.index')
            ->with('success', 'Enregistrement d\'alimentation mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $farmId = FarmHelper::getFarmId();
        $feedRecord = PoultryFeedRecord::where('farm_id', $farmId)->findOrFail($id);
        $feedRecord->delete();

        return redirect()->route('poultry.feed.index')
            ->with('success', 'Enregistrement d\'alimentation supprimé avec succès.');
    }
}
