<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PoultryFlock;
use App\Models\Cattle;
use App\Models\Crop;
use App\Models\Transaction;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * @group Analytics
     * 
     * Analytics avancés pour la volaille
     */
    public function getPoultryAnalytics(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->subMonths(6));
        $endDate = $request->get('end_date', now());

        // Production d'œufs par mois
        $eggProduction = DB::table('poultry_records')
            ->select(
                DB::raw('DATE_FORMAT(record_date, "%Y-%m") as month'),
                DB::raw('SUM(eggs_collected) as total_eggs'),
                DB::raw('AVG(eggs_collected) as avg_eggs'),
                DB::raw('COUNT(*) as record_count')
            )
            ->whereBetween('record_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Taux de ponte par lot
        $layingRate = DB::table('poultry_flocks')
            ->join('poultry_records', 'poultry_flocks.id', '=', 'poultry_records.flock_id')
            ->select(
                'poultry_flocks.name as flock_name',
                DB::raw('AVG(poultry_records.laying_rate) as avg_laying_rate'),
                DB::raw('SUM(poultry_records.eggs_collected) as total_eggs')
            )
            ->whereBetween('poultry_records.record_date', [$startDate, $endDate])
            ->groupBy('poultry_flocks.id', 'poultry_flocks.name')
            ->get();

        // Consommation d'aliments
        $feedConsumption = DB::table('poultry_records')
            ->select(
                DB::raw('DATE_FORMAT(record_date, "%Y-%m") as month'),
                DB::raw('SUM(feed_consumed) as total_feed'),
                DB::raw('AVG(feed_consumed) as avg_feed')
            )
            ->whereBetween('record_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prédictions basées sur les tendances
        $predictions = $this->calculatePoultryPredictions($eggProduction, $feedConsumption);

        return response()->json([
            'success' => true,
            'data' => [
                'period' => ['start_date' => $startDate, 'end_date' => $endDate],
                'egg_production' => $eggProduction,
                'laying_rate_by_flock' => $layingRate,
                'feed_consumption' => $feedConsumption,
                'predictions' => $predictions
            ]
        ]);
    }

    /**
     * @group Analytics
     * 
     * Analytics avancés pour le bétail
     */
    public function getCattleAnalytics(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->subMonths(6));
        $endDate = $request->get('end_date', now());

        // Production laitière
        $milkProduction = DB::table('cattle_records')
            ->select(
                DB::raw('DATE_FORMAT(record_date, "%Y-%m") as month'),
                DB::raw('SUM(milk_production) as total_milk'),
                DB::raw('AVG(milk_production) as avg_milk'),
                DB::raw('COUNT(DISTINCT cattle_id) as active_cows')
            )
            ->whereBetween('record_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Performance par vache
        $cowPerformance = DB::table('cattle')
            ->join('cattle_records', 'cattle.id', '=', 'cattle_records.cattle_id')
            ->select(
                'cattle.identification_number',
                'cattle.breed',
                DB::raw('AVG(cattle_records.milk_production) as avg_daily_milk'),
                DB::raw('COUNT(cattle_records.id) as record_count')
            )
            ->whereBetween('cattle_records.record_date', [$startDate, $endDate])
            ->groupBy('cattle.id', 'cattle.identification_number', 'cattle.breed')
            ->get();

        // Santé du troupeau
        $healthMetrics = DB::table('cattle_records')
            ->select(
                DB::raw('DATE_FORMAT(record_date, "%Y-%m") as month'),
                DB::raw('AVG(weight) as avg_weight'),
                DB::raw('COUNT(CASE WHEN health_status = "healthy" THEN 1 END) as healthy_count'),
                DB::raw('COUNT(CASE WHEN health_status = "sick" THEN 1 END) as sick_count')
            )
            ->whereBetween('record_date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => ['start_date' => $startDate, 'end_date' => $endDate],
                'milk_production' => $milkProduction,
                'cow_performance' => $cowPerformance,
                'health_metrics' => $healthMetrics
            ]
        ]);
    }

    /**
     * @group Analytics
     * 
     * Analytics avancés pour les cultures
     */
    public function getCropAnalytics(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', now()->subYear());
        $endDate = $request->get('end_date', now());

        // Rendements par culture
        $cropYields = DB::table('crops')
            ->select(
                'crop_type',
                DB::raw('AVG(expected_yield) as avg_expected_yield'),
                DB::raw('AVG(actual_yield) as avg_actual_yield'),
                DB::raw('COUNT(*) as crop_count')
            )
            ->whereBetween('planting_date', [$startDate, $endDate])
            ->groupBy('crop_type')
            ->get();

        // Performance par zone
        $zonePerformance = DB::table('crops')
            ->join('zones', 'crops.zone_id', '=', 'zones.id')
            ->select(
                'zones.name as zone_name',
                'crops.crop_type',
                DB::raw('AVG(crops.actual_yield) as avg_yield'),
                DB::raw('SUM(crops.actual_yield) as total_yield')
            )
            ->whereBetween('crops.planting_date', [$startDate, $endDate])
            ->groupBy('zones.id', 'zones.name', 'crops.crop_type')
            ->get();

        // Coûts de production
        $productionCosts = DB::table('crop_activities')
            ->join('crops', 'crop_activities.crop_id', '=', 'crops.id')
            ->select(
                'crops.crop_type',
                'crop_activities.activity_type',
                DB::raw('SUM(crop_activities.cost) as total_cost'),
                DB::raw('AVG(crop_activities.cost) as avg_cost')
            )
            ->whereBetween('crop_activities.activity_date', [$startDate, $endDate])
            ->groupBy('crops.crop_type', 'crop_activities.activity_type')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => ['start_date' => $startDate, 'end_date' => $endDate],
                'crop_yields' => $cropYields,
                'zone_performance' => $zonePerformance,
                'production_costs' => $productionCosts
            ]
        ]);
    }

    /**
     * @group Analytics
     * 
     * Vue d'ensemble de la ferme
     */
    public function getFarmOverview(): JsonResponse
    {
        // Statistiques générales
        $overview = [
            'poultry' => [
                'total_flocks' => PoultryFlock::count(),
                'total_birds' => PoultryFlock::sum('initial_count'),
                'active_flocks' => PoultryFlock::where('status', 'active')->count(),
            ],
            'cattle' => [
                'total_cattle' => Cattle::count(),
                'milking_cows' => Cattle::where('status', 'milking')->count(),
                'pregnant_cows' => Cattle::where('status', 'pregnant')->count(),
            ],
            'crops' => [
                'total_crops' => Crop::count(),
                'active_crops' => Crop::where('status', 'growing')->count(),
                'harvested_crops' => Crop::where('status', 'harvested')->count(),
            ],
            'financial' => [
                'total_income' => Transaction::where('type', 'income')->sum('amount'),
                'total_expense' => Transaction::where('type', 'expense')->sum('amount'),
                'net_profit' => Transaction::where('type', 'income')->sum('amount') - 
                               Transaction::where('type', 'expense')->sum('amount'),
            ]
        ];

        // Tendances des 6 derniers mois
        $trends = $this->calculateTrends();

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => $overview,
                'trends' => $trends
            ]
        ]);
    }

    private function calculatePoultryPredictions($eggProduction, $feedConsumption)
    {
        // Logique simple de prédiction basée sur les moyennes
        $avgEggs = $eggProduction->avg('total_eggs');
        $avgFeed = $feedConsumption->avg('total_feed');
        
        return [
            'predicted_monthly_eggs' => round($avgEggs * 1.05), // +5% de croissance
            'predicted_monthly_feed' => round($avgFeed * 1.02), // +2% de croissance
            'efficiency_ratio' => $avgEggs > 0 ? round($avgEggs / $avgFeed, 2) : 0
        ];
    }

    private function calculateTrends()
    {
        $sixMonthsAgo = now()->subMonths(6);
        
        return [
            'poultry_growth' => $this->calculateGrowthRate('poultry_flocks', $sixMonthsAgo),
            'cattle_growth' => $this->calculateGrowthRate('cattle', $sixMonthsAgo),
            'crop_growth' => $this->calculateGrowthRate('crops', $sixMonthsAgo),
            'revenue_growth' => $this->calculateRevenueGrowth($sixMonthsAgo)
        ];
    }

    private function calculateGrowthRate($table, $since)
    {
        $current = DB::table($table)->count();
        $previous = DB::table($table)->where('created_at', '<', $since)->count();
        
        if ($previous == 0) return 0;
        
        return round((($current - $previous) / $previous) * 100, 2);
    }

    private function calculateRevenueGrowth($since)
    {
        $current = Transaction::where('type', 'income')
            ->where('created_at', '>=', $since)
            ->sum('amount');
            
        $previous = Transaction::where('type', 'income')
            ->where('created_at', '<', $since)
            ->where('created_at', '>=', $since->subMonths(6))
            ->sum('amount');
            
        if ($previous == 0) return 0;
        
        return round((($current - $previous) / $previous) * 100, 2);
    }
}