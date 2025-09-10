<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PoultryRecord;
use App\Models\CattleRecord;
use App\Models\StockMovement;
use App\Models\CropActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Reports
 * 
 * APIs for generating reports and analytics
 */
class ReportController extends Controller
{
    /**
     * Get Poultry Production Report
     * 
     * Generate poultry production report for a date range
     * 
     * @queryParam start_date date required Start date
     * @queryParam end_date date required End date
     * @queryParam flock_id integer Filter by flock ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "summary": {...},
     *     "daily_data": [...],
     *     "flock_performance": [...]
     *   }
     * }
     */
    public function poultryProduction(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'flock_id' => 'nullable|exists:poultry_flocks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = PoultryRecord::with('flock')
            ->whereBetween('record_date', [$request->start_date, $request->end_date]);

        if ($request->has('flock_id')) {
            $query->where('flock_id', $request->flock_id);
        }

        $records = $query->get();

        // Summary statistics
        $summary = [
            'total_eggs' => $records->sum('eggs_collected'),
            'total_feed_consumed' => $records->sum('feed_consumed'),
            'total_mortality' => $records->sum('mortality_count'),
            'average_daily_eggs' => $records->avg('eggs_collected'),
            'average_daily_feed' => $records->avg('feed_consumed'),
            'total_days' => $records->count(),
        ];

        // Daily data
        $dailyData = $records->groupBy('record_date')->map(function ($dayRecords) {
            return [
                'date' => $dayRecords->first()->record_date,
                'total_eggs' => $dayRecords->sum('eggs_collected'),
                'total_feed' => $dayRecords->sum('feed_consumed'),
                'total_mortality' => $dayRecords->sum('mortality_count'),
                'flock_count' => $dayRecords->count(),
            ];
        })->values();

        // Flock performance
        $flockPerformance = $records->groupBy('flock_id')->map(function ($flockRecords) {
            $flock = $flockRecords->first()->flock;
            return [
                'flock_id' => $flock->id,
                'flock_number' => $flock->flock_number,
                'breed' => $flock->breed,
                'total_eggs' => $flockRecords->sum('eggs_collected'),
                'total_feed' => $flockRecords->sum('feed_consumed'),
                'total_mortality' => $flockRecords->sum('mortality_count'),
                'average_daily_eggs' => $flockRecords->avg('eggs_collected'),
                'feed_efficiency' => $flockRecords->sum('feed_consumed') > 0 
                    ? round($flockRecords->sum('eggs_collected') / $flockRecords->sum('feed_consumed'), 2)
                    : 0,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'daily_data' => $dailyData,
                'flock_performance' => $flockPerformance,
            ]
        ]);
    }

    /**
     * Get Cattle Production Report
     * 
     * Generate cattle production report for a date range
     * 
     * @queryParam start_date date required Start date
     * @queryParam end_date date required End date
     * @queryParam cattle_id integer Filter by cattle ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "summary": {...},
     *     "daily_data": [...],
     *     "cattle_performance": [...]
     *   }
     * }
     */
    public function cattleProduction(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cattle_id' => 'nullable|exists:cattle,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = CattleRecord::with('cattle')
            ->whereBetween('record_date', [$request->start_date, $request->end_date]);

        if ($request->has('cattle_id')) {
            $query->where('cattle_id', $request->cattle_id);
        }

        $records = $query->get();

        // Summary statistics
        $summary = [
            'total_milk_production' => $records->sum('milk_production'),
            'average_daily_milk' => $records->avg('milk_production'),
            'total_records' => $records->count(),
            'healthy_days' => $records->where('health_status', 'healthy')->count(),
            'sick_days' => $records->where('health_status', 'sick')->count(),
        ];

        // Daily data
        $dailyData = $records->groupBy('record_date')->map(function ($dayRecords) {
            return [
                'date' => $dayRecords->first()->record_date,
                'total_milk' => $dayRecords->sum('milk_production'),
                'cattle_count' => $dayRecords->count(),
                'healthy_count' => $dayRecords->where('health_status', 'healthy')->count(),
            ];
        })->values();

        // Cattle performance
        $cattlePerformance = $records->groupBy('cattle_id')->map(function ($cattleRecords) {
            $cattle = $cattleRecords->first()->cattle;
            return [
                'cattle_id' => $cattle->id,
                'tag_number' => $cattle->tag_number,
                'name' => $cattle->name,
                'breed' => $cattle->breed,
                'total_milk' => $cattleRecords->sum('milk_production'),
                'average_daily_milk' => $cattleRecords->avg('milk_production'),
                'health_percentage' => $cattleRecords->count() > 0 
                    ? round(($cattleRecords->where('health_status', 'healthy')->count() / $cattleRecords->count()) * 100, 2)
                    : 0,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'daily_data' => $dailyData,
                'cattle_performance' => $cattlePerformance,
            ]
        ]);
    }

    /**
     * Get Stock Movement Report
     * 
     * Generate stock movement report for a date range
     * 
     * @queryParam start_date date required Start date
     * @queryParam end_date date required End date
     * @queryParam category_id integer Filter by category ID
     * @queryParam type string Filter by movement type (in, out, adjustment)
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "summary": {...},
     *     "movements": [...],
     *     "category_breakdown": [...]
     *   }
     * }
     */
    public function stockMovements(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:stock_categories,id',
            'type' => 'nullable|in:in,out,adjustment',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = StockMovement::with(['stockItem.category', 'user'])
            ->whereBetween('movement_date', [$request->start_date, $request->end_date]);

        if ($request->has('category_id')) {
            $query->whereHas('stockItem', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->get();

        // Summary statistics
        $summary = [
            'total_movements' => $movements->count(),
            'total_in' => $movements->where('type', 'in')->sum('quantity'),
            'total_out' => $movements->where('type', 'out')->sum('quantity'),
            'total_adjustments' => $movements->where('type', 'adjustment')->count(),
            'total_value_in' => $movements->where('type', 'in')->sum(function ($m) {
                return $m->quantity * ($m->unit_cost ?? 0);
            }),
            'total_value_out' => $movements->where('type', 'out')->sum(function ($m) {
                return $m->quantity * ($m->unit_cost ?? 0);
            }),
        ];

        // Category breakdown
        $categoryBreakdown = $movements->groupBy('stockItem.category.name')->map(function ($categoryMovements) {
            return [
                'category' => $categoryMovements->first()->stockItem->category->name,
                'total_movements' => $categoryMovements->count(),
                'total_in' => $categoryMovements->where('type', 'in')->sum('quantity'),
                'total_out' => $categoryMovements->where('type', 'out')->sum('quantity'),
                'total_value' => $categoryMovements->sum(function ($m) {
                    return $m->quantity * ($m->unit_cost ?? 0);
                }),
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'movements' => $movements,
                'category_breakdown' => $categoryBreakdown,
            ]
        ]);
    }

    /**
     * Get Crop Performance Report
     * 
     * Generate crop performance report
     * 
     * @queryParam year integer Filter by year
     * @queryParam crop_name string Filter by crop name
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "summary": {...},
     *     "crop_performance": [...],
     *     "activities_summary": [...]
     *   }
     * }
     */
    public function cropPerformance(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $query = \App\Models\Crop::with(['zone', 'activities'])
            ->whereYear('planting_date', $year);

        if ($request->has('crop_name')) {
            $query->where('name', 'like', "%{$request->crop_name}%");
        }

        $crops = $query->get();

        // Summary statistics
        $summary = [
            'total_crops' => $crops->count(),
            'total_area' => $crops->sum('planted_area'),
            'harvested_crops' => $crops->where('status', 'harvested')->count(),
            'total_yield' => $crops->where('status', 'harvested')->sum('actual_yield'),
            'average_yield_per_sqm' => $crops->where('status', 'harvested')->avg(function ($crop) {
                return $crop->planted_area > 0 ? $crop->actual_yield / $crop->planted_area : 0;
            }),
        ];

        // Crop performance
        $cropPerformance = $crops->map(function ($crop) {
            return [
                'crop_id' => $crop->id,
                'name' => $crop->name,
                'variety' => $crop->variety,
                'zone_name' => $crop->zone->name ?? 'N/A',
                'planted_area' => $crop->planted_area,
                'expected_yield' => $crop->expected_yield,
                'actual_yield' => $crop->actual_yield,
                'yield_per_sqm' => $crop->planted_area > 0 ? $crop->actual_yield / $crop->planted_area : 0,
                'status' => $crop->status,
                'planting_date' => $crop->planting_date,
                'harvest_date' => $crop->actual_harvest_date,
                'activities_count' => $crop->activities->count(),
            ];
        });

        // Activities summary
        $activitiesSummary = \App\Models\CropActivity::whereHas('crop', function ($q) use ($year) {
            $q->whereYear('planting_date', $year);
        })->selectRaw('activity_type, COUNT(*) as count, SUM(cost) as total_cost')
            ->groupBy('activity_type')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => $summary,
                'crop_performance' => $cropPerformance,
                'activities_summary' => $activitiesSummary,
            ]
        ]);
    }

    /**
     * Get Financial Summary Report
     * 
     * Generate financial summary report
     * 
     * @queryParam start_date date required Start date
     * @queryParam end_date date required End date
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "revenue": {...},
     *     "expenses": {...},
     *     "profit_loss": {...}
     *   }
     * }
     */
    public function financialSummary(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // This is a simplified financial report
        // In a real application, you would have dedicated financial tables
        
        $revenue = [
            'egg_sales' => 0, // Would be calculated from sales records
            'milk_sales' => 0, // Would be calculated from sales records
            'crop_sales' => 0, // Would be calculated from sales records
            'total_revenue' => 0,
        ];

        $expenses = [
            'feed_costs' => StockMovement::where('type', 'out')
                ->whereBetween('movement_date', [$request->start_date, $request->end_date])
                ->whereHas('stockItem.category', function ($q) {
                    $q->where('type', 'animal_feed');
                })
                ->sum(DB::raw('quantity * unit_cost')),
            
            'veterinary_costs' => StockMovement::where('type', 'out')
                ->whereBetween('movement_date', [$request->start_date, $request->end_date])
                ->whereHas('stockItem.category', function ($q) {
                    $q->where('type', 'veterinary_products');
                })
                ->sum(DB::raw('quantity * unit_cost')),
            
            'crop_activities_costs' => CropActivity::whereBetween('activity_date', [$request->start_date, $request->end_date])
                ->sum('cost'),
            
            'total_expenses' => 0,
        ];

        $expenses['total_expenses'] = array_sum($expenses);

        $profitLoss = [
            'gross_profit' => $revenue['total_revenue'] - $expenses['total_expenses'],
            'profit_margin' => $revenue['total_revenue'] > 0 
                ? (($revenue['total_revenue'] - $expenses['total_expenses']) / $revenue['total_revenue']) * 100 
                : 0,
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'revenue' => $revenue,
                'expenses' => $expenses,
                'profit_loss' => $profitLoss,
            ]
        ]);
    }
}
