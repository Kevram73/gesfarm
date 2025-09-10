<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use App\Models\PoultryFlock;
use App\Models\Cattle;
use App\Models\Crop;
use App\Models\Task;
use App\Models\PoultryRecord;
use App\Models\CattleRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Dashboard
 * 
 * APIs for dashboard data and KPIs
 */
class DashboardController extends Controller
{
    /**
     * Get Dashboard Data
     * 
     * Retrieve key performance indicators and summary data
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "kpis": {
     *       "stock_alerts": {...},
     *       "poultry_stats": {...},
     *       "cattle_stats": {...},
     *       "crop_stats": {...},
     *       "tasks": {...}
     *     },
     *     "recent_activities": [...]
     *   }
     * }
     */
    public function index(Request $request)
    {
        $kpis = [
            'stock_alerts' => $this->getStockAlerts(),
            'poultry_stats' => $this->getPoultryStats(),
            'cattle_stats' => $this->getCattleStats(),
            'crop_stats' => $this->getCropStats(),
            'tasks' => $this->getTaskStats(),
        ];

        $recentActivities = $this->getRecentActivities();

        return response()->json([
            'status' => 'success',
            'data' => [
                'kpis' => $kpis,
                'recent_activities' => $recentActivities
            ]
        ]);
    }

    /**
     * Get Stock Alerts
     * 
     * Get stock items with low stock or expired items
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "low_stock": [...],
     *     "expired_items": [...],
     *     "expiring_soon": [...]
     *   }
     * }
     */
    public function stockAlerts()
    {
        $lowStock = StockItem::whereRaw('current_quantity <= minimum_quantity')
            ->with('category')
            ->get();

        $expiredItems = StockItem::where('expiry_date', '<', now())
            ->with('category')
            ->get();

        $expiringSoon = StockItem::whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->with('category')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'low_stock' => $lowStock,
                'expired_items' => $expiredItems,
                'expiring_soon' => $expiringSoon
            ]
        ]);
    }

    /**
     * Get Poultry Statistics
     * 
     * Get poultry performance statistics
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "total_flocks": 0,
     *     "total_birds": 0,
     *     "average_mortality_rate": 0,
     *     "daily_egg_production": 0,
     *     "feed_consumption": 0
     *   }
     * }
     */
    public function poultryStats()
    {
        $totalFlocks = PoultryFlock::where('status', 'active')->count();
        $totalBirds = PoultryFlock::where('status', 'active')->sum('current_quantity');
        
        $mortalityData = PoultryFlock::selectRaw('
            AVG((initial_quantity - current_quantity) / initial_quantity * 100) as avg_mortality_rate
        ')->where('status', 'active')->first();

        $todayRecords = PoultryRecord::whereDate('record_date', today())
            ->selectRaw('SUM(eggs_collected) as total_eggs, SUM(feed_consumed) as total_feed')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_flocks' => $totalFlocks,
                'total_birds' => $totalBirds,
                'average_mortality_rate' => round($mortalityData->avg_mortality_rate ?? 0, 2),
                'daily_egg_production' => $todayRecords->total_eggs ?? 0,
                'feed_consumption' => $todayRecords->total_feed ?? 0
            ]
        ]);
    }

    /**
     * Get Cattle Statistics
     * 
     * Get cattle performance statistics
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "total_cattle": 0,
     *     "milk_production_today": 0,
     *     "average_weight": 0,
     *     "health_status": {...}
     *   }
     * }
     */
    public function cattleStats()
    {
        $totalCattle = Cattle::where('status', 'active')->count();
        
        $todayMilk = CattleRecord::whereDate('record_date', today())
            ->sum('milk_production');

        $averageWeight = Cattle::where('status', 'active')
            ->whereNotNull('current_weight')
            ->avg('current_weight');

        $healthStatus = CattleRecord::whereDate('record_date', today())
            ->selectRaw('health_status, COUNT(*) as count')
            ->groupBy('health_status')
            ->pluck('count', 'health_status');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_cattle' => $totalCattle,
                'milk_production_today' => $todayMilk,
                'average_weight' => round($averageWeight ?? 0, 2),
                'health_status' => $healthStatus
            ]
        ]);
    }

    /**
     * Get Crop Statistics
     * 
     * Get crop performance statistics
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "total_crops": 0,
     *     "harvested_crops": 0,
     *     "average_yield_per_sqm": 0,
     *     "upcoming_harvests": 0
     *   }
     * }
     */
    public function cropStats()
    {
        $totalCrops = Crop::count();
        $harvestedCrops = Crop::where('status', 'harvested')->count();
        
        $averageYield = Crop::where('status', 'harvested')
            ->whereNotNull('actual_yield')
            ->whereNotNull('planted_area')
            ->selectRaw('AVG(actual_yield / planted_area) as avg_yield_per_sqm')
            ->first();

        $upcomingHarvests = Crop::where('status', 'growing')
            ->where('expected_harvest_date', '<=', now()->addDays(30))
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_crops' => $totalCrops,
                'harvested_crops' => $harvestedCrops,
                'average_yield_per_sqm' => round($averageYield->avg_yield_per_sqm ?? 0, 2),
                'upcoming_harvests' => $upcomingHarvests
            ]
        ]);
    }

    /**
     * Get Task Statistics
     * 
     * Get task management statistics
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "pending_tasks": 0,
     *     "overdue_tasks": 0,
     *     "completed_today": 0,
     *     "tasks_by_priority": {...}
     *   }
     * }
     */
    public function taskStats()
    {
        $pendingTasks = Task::where('status', 'pending')->count();
        $overdueTasks = Task::where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->count();
        $completedToday = Task::where('status', 'completed')
            ->whereDate('completed_date', today())
            ->count();

        $tasksByPriority = Task::where('status', '!=', 'completed')
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority');

        return response()->json([
            'status' => 'success',
            'data' => [
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
                'completed_today' => $completedToday,
                'tasks_by_priority' => $tasksByPriority
            ]
        ]);
    }

    private function getStockAlerts()
    {
        return [
            'low_stock_count' => StockItem::whereRaw('current_quantity <= minimum_quantity')->count(),
            'expired_count' => StockItem::where('expiry_date', '<', now())->count(),
            'expiring_soon_count' => StockItem::whereBetween('expiry_date', [now(), now()->addDays(30)])->count(),
        ];
    }

    private function getPoultryStats()
    {
        $totalFlocks = PoultryFlock::where('status', 'active')->count();
        $totalBirds = PoultryFlock::where('status', 'active')->sum('current_quantity');
        
        $todayEggs = PoultryRecord::whereDate('record_date', today())->sum('eggs_collected');
        
        return [
            'total_flocks' => $totalFlocks,
            'total_birds' => $totalBirds,
            'daily_egg_production' => $todayEggs,
        ];
    }

    private function getCattleStats()
    {
        $totalCattle = Cattle::where('status', 'active')->count();
        $todayMilk = CattleRecord::whereDate('record_date', today())->sum('milk_production');
        
        return [
            'total_cattle' => $totalCattle,
            'daily_milk_production' => $todayMilk,
        ];
    }

    private function getCropStats()
    {
        $totalCrops = Crop::count();
        $harvestedCrops = Crop::where('status', 'harvested')->count();
        $upcomingHarvests = Crop::where('status', 'growing')
            ->where('expected_harvest_date', '<=', now()->addDays(30))
            ->count();
        
        return [
            'total_crops' => $totalCrops,
            'harvested_crops' => $harvestedCrops,
            'upcoming_harvests' => $upcomingHarvests,
        ];
    }

    private function getTaskStats()
    {
        $pendingTasks = Task::where('status', 'pending')->count();
        $overdueTasks = Task::where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->count();
        
        return [
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
        ];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent poultry records
        $poultryRecords = PoultryRecord::with('flock')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($record) {
                return [
                    'type' => 'poultry_record',
                    'description' => "Recorded data for flock {$record->flock->flock_number}",
                    'date' => $record->record_date,
                    'user' => $record->recordedBy->name ?? 'Unknown'
                ];
            });

        // Recent cattle records
        $cattleRecords = CattleRecord::with('cattle')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($record) {
                return [
                    'type' => 'cattle_record',
                    'description' => "Recorded data for cattle {$record->cattle->tag_number}",
                    'date' => $record->record_date,
                    'user' => $record->recordedBy->name ?? 'Unknown'
                ];
            });

        // Recent stock movements
        $stockMovements = \App\Models\StockMovement::with(['stockItem', 'user'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($movement) {
                return [
                    'type' => 'stock_movement',
                    'description' => "Stock movement: {$movement->type} {$movement->quantity} {$movement->stockItem->name}",
                    'date' => $movement->movement_date,
                    'user' => $movement->user->name ?? 'Unknown'
                ];
            });

        return $activities
            ->merge($poultryRecords)
            ->merge($cattleRecords)
            ->merge($stockMovements)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }
}
