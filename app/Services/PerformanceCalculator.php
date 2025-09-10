<?php

namespace App\Services;

use App\Models\PoultryFlock;
use App\Models\PoultryRecord;
use App\Models\Cattle;
use App\Models\CattleRecord;
use App\Models\Crop;
use Carbon\Carbon;

class PerformanceCalculator
{
    /**
     * Calculate poultry flock performance metrics
     */
    public function calculatePoultryPerformance(PoultryFlock $flock, $days = 30)
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays($days);

        $records = PoultryRecord::where('flock_id', $flock->id)
            ->whereBetween('record_date', [$startDate, $endDate])
            ->get();

        if ($records->isEmpty()) {
            return [
                'mortality_rate' => 0,
                'egg_production_rate' => 0,
                'feed_efficiency' => 0,
                'average_daily_eggs' => 0,
                'average_daily_feed' => 0,
                'total_eggs' => 0,
                'total_feed' => 0,
                'total_mortality' => 0,
            ];
        }

        $totalEggs = $records->sum('eggs_collected');
        $totalFeed = $records->sum('feed_consumed');
        $totalMortality = $records->sum('mortality_count');
        $recordDays = $records->count();

        $averageDailyEggs = $recordDays > 0 ? $totalEggs / $recordDays : 0;
        $averageDailyFeed = $recordDays > 0 ? $totalFeed / $recordDays : 0;

        // Calculate mortality rate
        $mortalityRate = $flock->initial_quantity > 0 
            ? (($flock->initial_quantity - $flock->current_quantity) / $flock->initial_quantity) * 100 
            : 0;

        // Calculate egg production rate (eggs per bird per day)
        $averageBirds = $flock->current_quantity; // Simplified calculation
        $eggProductionRate = $averageBirds > 0 ? $averageDailyEggs / $averageBirds : 0;

        // Calculate feed efficiency (eggs per kg of feed)
        $feedEfficiency = $totalFeed > 0 ? $totalEggs / $totalFeed : 0;

        return [
            'mortality_rate' => round($mortalityRate, 2),
            'egg_production_rate' => round($eggProductionRate, 2),
            'feed_efficiency' => round($feedEfficiency, 2),
            'average_daily_eggs' => round($averageDailyEggs, 2),
            'average_daily_feed' => round($averageDailyFeed, 2),
            'total_eggs' => $totalEggs,
            'total_feed' => round($totalFeed, 2),
            'total_mortality' => $totalMortality,
        ];
    }

    /**
     * Calculate cattle performance metrics
     */
    public function calculateCattlePerformance(Cattle $cattle, $days = 30)
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays($days);

        $records = CattleRecord::where('cattle_id', $cattle->id)
            ->whereBetween('record_date', [$startDate, $endDate])
            ->get();

        if ($records->isEmpty()) {
            return [
                'average_daily_milk' => 0,
                'total_milk_production' => 0,
                'health_percentage' => 0,
                'weight_gain' => 0,
                'record_days' => 0,
            ];
        }

        $totalMilk = $records->sum('milk_production');
        $recordDays = $records->count();
        $healthyDays = $records->where('health_status', 'healthy')->count();

        $averageDailyMilk = $recordDays > 0 ? $totalMilk / $recordDays : 0;
        $healthPercentage = $recordDays > 0 ? ($healthyDays / $recordDays) * 100 : 0;

        // Calculate weight gain
        $firstWeight = $records->sortBy('record_date')->first()->weight ?? 0;
        $lastWeight = $records->sortByDesc('record_date')->first()->weight ?? 0;
        $weightGain = $lastWeight - $firstWeight;

        return [
            'average_daily_milk' => round($averageDailyMilk, 2),
            'total_milk_production' => round($totalMilk, 2),
            'health_percentage' => round($healthPercentage, 2),
            'weight_gain' => round($weightGain, 2),
            'record_days' => $recordDays,
        ];
    }

    /**
     * Calculate crop performance metrics
     */
    public function calculateCropPerformance(Crop $crop)
    {
        $plantedArea = $crop->planted_area;
        $actualYield = $crop->actual_yield ?? 0;
        $expectedYield = $crop->expected_yield ?? 0;

        $yieldPerSquareMeter = $plantedArea > 0 ? $actualYield / $plantedArea : 0;
        $yieldEfficiency = $expectedYield > 0 ? ($actualYield / $expectedYield) * 100 : 0;

        // Calculate growth period
        $growthPeriod = 0;
        if ($crop->planting_date && $crop->actual_harvest_date) {
            $growthPeriod = $crop->planting_date->diffInDays($crop->actual_harvest_date);
        } elseif ($crop->planting_date && $crop->expected_harvest_date) {
            $growthPeriod = $crop->planting_date->diffInDays($crop->expected_harvest_date);
        }

        // Calculate cost per unit
        $totalCost = $crop->activities->sum('cost');
        $costPerKg = $actualYield > 0 ? $totalCost / $actualYield : 0;
        $costPerSquareMeter = $plantedArea > 0 ? $totalCost / $plantedArea : 0;

        return [
            'yield_per_square_meter' => round($yieldPerSquareMeter, 2),
            'yield_efficiency' => round($yieldEfficiency, 2),
            'growth_period_days' => $growthPeriod,
            'total_cost' => round($totalCost, 2),
            'cost_per_kg' => round($costPerKg, 2),
            'cost_per_square_meter' => round($costPerSquareMeter, 2),
            'activities_count' => $crop->activities->count(),
        ];
    }

    /**
     * Calculate overall farm performance
     */
    public function calculateFarmPerformance($days = 30)
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays($days);

        // Poultry performance
        $poultryFlocks = PoultryFlock::where('status', 'active')->get();
        $totalPoultryPerformance = [
            'total_flocks' => $poultryFlocks->count(),
            'total_birds' => $poultryFlocks->sum('current_quantity'),
            'total_eggs' => 0,
            'total_feed_consumed' => 0,
            'average_mortality_rate' => 0,
        ];

        foreach ($poultryFlocks as $flock) {
            $performance = $this->calculatePoultryPerformance($flock, $days);
            $totalPoultryPerformance['total_eggs'] += $performance['total_eggs'];
            $totalPoultryPerformance['total_feed_consumed'] += $performance['total_feed'];
        }

        if ($poultryFlocks->count() > 0) {
            $totalPoultryPerformance['average_mortality_rate'] = $poultryFlocks->avg(function ($flock) {
                return $flock->mortality_rate;
            });
        }

        // Cattle performance
        $cattle = Cattle::where('status', 'active')->get();
        $totalCattlePerformance = [
            'total_cattle' => $cattle->count(),
            'total_milk_production' => 0,
            'average_daily_milk' => 0,
        ];

        foreach ($cattle as $animal) {
            $performance = $this->calculateCattlePerformance($animal, $days);
            $totalCattlePerformance['total_milk_production'] += $performance['total_milk_production'];
        }

        if ($cattle->count() > 0) {
            $totalCattlePerformance['average_daily_milk'] = $totalCattlePerformance['total_milk_production'] / $days;
        }

        // Crop performance
        $crops = Crop::where('status', 'harvested')->get();
        $totalCropPerformance = [
            'total_crops' => $crops->count(),
            'total_area' => $crops->sum('planted_area'),
            'total_yield' => $crops->sum('actual_yield'),
            'average_yield_per_sqm' => 0,
        ];

        if ($totalCropPerformance['total_area'] > 0) {
            $totalCropPerformance['average_yield_per_sqm'] = $totalCropPerformance['total_yield'] / $totalCropPerformance['total_area'];
        }

        return [
            'period_days' => $days,
            'poultry' => $totalPoultryPerformance,
            'cattle' => $totalCattlePerformance,
            'crops' => $totalCropPerformance,
        ];
    }

    /**
     * Calculate feed conversion ratio for poultry
     */
    public function calculateFeedConversionRatio(PoultryFlock $flock, $days = 30)
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays($days);

        $records = PoultryRecord::where('flock_id', $flock->id)
            ->whereBetween('record_date', [$startDate, $endDate])
            ->get();

        if ($records->isEmpty()) {
            return 0;
        }

        $totalFeed = $records->sum('feed_consumed');
        $totalEggs = $records->sum('eggs_collected');

        // FCR = Feed consumed (kg) / Eggs produced (kg)
        // Assuming average egg weight of 60g
        $totalEggWeight = $totalEggs * 0.06; // Convert to kg

        return $totalEggWeight > 0 ? round($totalFeed / $totalEggWeight, 2) : 0;
    }

    /**
     * Calculate milk production efficiency for cattle
     */
    public function calculateMilkProductionEfficiency(Cattle $cattle, $days = 30)
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays($days);

        $records = CattleRecord::where('cattle_id', $cattle->id)
            ->whereBetween('record_date', [$startDate, $endDate])
            ->get();

        if ($records->isEmpty()) {
            return 0;
        }

        $totalMilk = $records->sum('milk_production');
        $recordDays = $records->count();

        // Efficiency = Total milk / Number of days
        return $recordDays > 0 ? round($totalMilk / $recordDays, 2) : 0;
    }
}
