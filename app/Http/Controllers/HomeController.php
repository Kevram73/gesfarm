<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Harvest;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Crop;
use App\Models\Field;
use App\Models\Livestock;
use App\Models\Equipment;
use App\Models\FarmTask;
use App\Models\Employee;
use App\Models\Inventory;
use App\Models\EggProduction;
use App\Models\MilkProduction;
use App\Models\HealthRecord;
use App\Models\FieldCrop;
use App\Helpers\FarmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Afficher le dashboard principal
     */
    public function index()
    {
        $farm = FarmHelper::getFarm();
        $farmId = $farm->id;

        // Statistiques générales
        $stats = [
            'total_customers' => Customer::where('farm_id', $farmId)->count(),
            'total_employees' => Employee::where('farm_id', $farmId)->count(),
            'total_crops' => Crop::where('farm_id', $farmId)->count(),
            'total_fields' => Field::where('farm_id', $farmId)->count(),
            'total_livestock' => Livestock::where('farm_id', $farmId)->count(),
            'total_equipment' => Equipment::where('farm_id', $farmId)->count(),
        ];

        // Revenus
        $revenueQuery = Payment::where('farm_id', $farmId)
            ->where('type', 'INCOME')
            ->where('status', 'COMPLETED');
        
        $stats['total_revenue'] = $revenueQuery->sum('amount');
        $stats['revenue_this_month'] = (clone $revenueQuery)->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $stats['revenue_last_month'] = (clone $revenueQuery)->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // Tâches
        $tasksQuery = FarmTask::where('farm_id', $farmId);
        
        $stats['total_tasks'] = $tasksQuery->count();
        $stats['pending_tasks'] = (clone $tasksQuery)->where('status', 'PENDING')->count();
        $stats['in_progress_tasks'] = (clone $tasksQuery)->where('status', 'IN_PROGRESS')->count();
        $stats['completed_tasks'] = (clone $tasksQuery)->where('status', 'COMPLETED')->count();

        // Récoltes
        $harvestsQuery = Harvest::where('farm_id', $farmId);
        
        $stats['total_harvests'] = $harvestsQuery->count();
        $stats['harvests_this_month'] = (clone $harvestsQuery)->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();
        $stats['pending_harvests'] = (clone $harvestsQuery)->where('status', 'PENDING')->count();

        // Inventaire
        $inventoryQuery = Inventory::where('farm_id', $farmId);
        
        $stats['total_inventory_items'] = $inventoryQuery->count();
        $stats['low_stock_items'] = (clone $inventoryQuery)->where('is_low_stock', true)->count();

        // KPIs AVICOLE - Mortalité %
        $totalPoultry = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->count();
        $deceasedPoultry = Livestock::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('type', 'LIKE', '%POULTRY%')
                  ->orWhere('type', 'LIKE', '%VOLAILLE%');
            })
            ->where('status', 'DECEASED')
            ->count();
        $stats['poultry_mortality_rate'] = $totalPoultry > 0 
            ? round(($deceasedPoultry / $totalPoultry) * 100, 2) 
            : 0;

        // KPIs AVICOLE - Ponte/jour
        $today = now()->format('Y-m-d');
        $stats['eggs_per_day'] = EggProduction::where('farm_id', $farmId)
            ->where('date', $today)
            ->sum('egg_count');
        
        // KPIs RUMINANTS - Lait/jour
        $stats['milk_per_day'] = MilkProduction::where('farm_id', $farmId)
            ->where('date', $today)
            ->sum('quantity');

        // Alertes vaccins (prochains vaccins à venir dans les 30 jours)
        $upcomingVaccinations = HealthRecord::whereHas('livestock', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })
        ->where('type', 'VACCINATION')
        ->where('date', '>=', now())
        ->where('date', '<=', now()->addDays(30))
        ->with('livestock')
        ->orderBy('date', 'asc')
        ->limit(10)
        ->get();

        // Ruptures stock
        $stockAlerts = Inventory::where('farm_id', $farmId)
            ->where('is_low_stock', true)
            ->orderBy('current_stock', 'asc')
            ->limit(10)
            ->get();

        // Tâches du jour
        $todayTasks = FarmTask::where('farm_id', $farmId)
            ->where(function($q) {
                $q->where('due_date', now()->format('Y-m-d'))
                  ->orWhere(function($q2) {
                      $q2->where('status', 'PENDING')
                         ->orWhere('status', 'IN_PROGRESS');
                  });
            })
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        // Rendement moyen au m² par culture (basé sur les champs)
        $cropYields = DB::table('harvests')
            ->join('fields', 'harvests.field_id', '=', 'fields.id')
            ->join('crops', 'harvests.crop_id', '=', 'crops.id')
            ->where('harvests.farm_id', $farmId)
            ->whereNotNull('fields.area')
            ->where('fields.area', '>', 0)
            ->select(
                'crops.id',
                'crops.name',
                DB::raw('AVG(harvests.quantity / fields.area) as avg_yield_per_m2'),
                DB::raw('COUNT(harvests.id) as harvest_count')
            )
            ->groupBy('crops.id', 'crops.name')
            ->get();

        // Alertes semis/récolte/entretien
        $upcomingPlantings = FieldCrop::whereHas('field', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })
        ->where('planting_date', '>=', now())
        ->where('planting_date', '<=', now()->addDays(7))
        ->with(['field', 'crop'])
        ->orderBy('planting_date', 'asc')
        ->get();

        $upcomingHarvests = FieldCrop::whereHas('field', function($q) use ($farmId) {
            $q->where('farm_id', $farmId);
        })
        ->where('expected_harvest_date', '>=', now())
        ->where('expected_harvest_date', '<=', now()->addDays(7))
        ->where('status', 'IN_PROGRESS')
        ->with(['field', 'crop'])
        ->orderBy('expected_harvest_date', 'asc')
        ->get();

        // Récoltes récentes
        $recentHarvests = (clone $harvestsQuery)
            ->with(['crop', 'field', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Tâches récentes
        $recentTasks = (clone $tasksQuery)
            ->with(['assignedTo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Paiements récents
        $recentPayments = Payment::where('farm_id', $farmId)
            ->with(['customer', 'harvest'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home.index', compact(
            'stats', 
            'recentHarvests', 
            'recentTasks', 
            'recentPayments', 
            'farm',
            'upcomingVaccinations',
            'stockAlerts',
            'todayTasks',
            'cropYields',
            'upcomingPlantings',
            'upcomingHarvests'
        ));
    }

    /**
     * Afficher le profil de l'utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load('farm');
        
        return view('home.profile', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('home.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}
