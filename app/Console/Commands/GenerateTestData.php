<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\Zone;
use App\Models\PoultryFlock;
use App\Models\PoultryRecord;
use App\Models\Cattle;
use App\Models\CattleRecord;
use App\Models\Crop;
use App\Models\CropActivity;
use App\Models\Task;
use App\Models\StockMovement;
use Carbon\Carbon;

class GenerateTestData extends Command
{
    protected $signature = 'farm:generate-test-data {--count=10 : Number of records to generate}';
    protected $description = 'Generate test data for the farm management system';

    public function handle()
    {
        $count = $this->option('count');
        
        $this->info('Generating test data...');
        
        // Generate zones
        $this->generateZones();
        
        // Generate stock items
        $this->generateStockItems();
        
        // Generate poultry data
        $this->generatePoultryData($count);
        
        // Generate cattle data
        $this->generateCattleData($count);
        
        // Generate crop data
        $this->generateCropData($count);
        
        // Generate tasks
        $this->generateTasks($count);
        
        $this->info('Test data generated successfully!');
    }

    private function generateZones()
    {
        $zones = [
            ['name' => 'Parcelle Nord', 'type' => 'cultivation', 'area' => 5000],
            ['name' => 'Parcelle Sud', 'type' => 'cultivation', 'area' => 3000],
            ['name' => 'Enclos Volailles 1', 'type' => 'enclosure', 'area' => 200],
            ['name' => 'Enclos Volailles 2', 'type' => 'enclosure', 'area' => 200],
            ['name' => 'Pâturage Bovins', 'type' => 'pasture', 'area' => 2000],
            ['name' => 'Bâtiment Principal', 'type' => 'building', 'area' => 500],
        ];

        foreach ($zones as $zoneData) {
            Zone::firstOrCreate(
                ['name' => $zoneData['name']],
                array_merge($zoneData, [
                    'description' => 'Zone générée automatiquement',
                    'coordinates' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [0, 0], [1, 0], [1, 1], [0, 1], [0, 0]
                        ]]
                    ],
                    'status' => 'active'
                ])
            );
        }
    }

    private function generateStockItems()
    {
        $categories = StockCategory::all();
        
        if ($categories->isEmpty()) {
            $this->warn('No stock categories found. Please run the seeders first.');
            return;
        }

        $stockItems = [
            ['name' => 'Aliment pour poules pondeuses', 'sku' => 'ALIM-POULE-001', 'unit' => 'kg'],
            ['name' => 'Aliment pour poulets de chair', 'sku' => 'ALIM-POULET-001', 'unit' => 'kg'],
            ['name' => 'Aliment pour bovins', 'sku' => 'ALIM-BOVIN-001', 'unit' => 'kg'],
            ['name' => 'Vaccin aviaire', 'sku' => 'VACCIN-AVI-001', 'unit' => 'dose'],
            ['name' => 'Engrais NPK', 'sku' => 'ENGRAIS-NPK-001', 'unit' => 'kg'],
            ['name' => 'Semences de maïs', 'sku' => 'SEM-MAIS-001', 'unit' => 'kg'],
        ];

        foreach ($stockItems as $itemData) {
            $category = $categories->random();
            StockItem::firstOrCreate(
                ['sku' => $itemData['sku']],
                array_merge($itemData, [
                    'category_id' => $category->id,
                    'current_quantity' => rand(100, 1000),
                    'minimum_quantity' => rand(50, 200),
                    'unit_cost' => rand(10, 100) / 10,
                    'supplier' => 'Fournisseur ' . rand(1, 5),
                ])
            );
        }
    }

    private function generatePoultryData($count)
    {
        $zones = Zone::where('type', 'enclosure')->get();
        
        if ($zones->isEmpty()) {
            $this->warn('No poultry zones found.');
            return;
        }

        for ($i = 1; $i <= $count; $i++) {
            $flock = PoultryFlock::create([
                'flock_number' => 'FLOCK-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => ['layer', 'broiler', 'duck'][rand(0, 2)],
                'breed' => ['Rhode Island Red', 'Leghorn', 'Sussex'][rand(0, 2)],
                'initial_quantity' => rand(100, 500),
                'current_quantity' => rand(80, 450),
                'arrival_date' => Carbon::now()->subDays(rand(30, 180)),
                'zone_id' => $zones->random()->id,
                'notes' => 'Lot de test généré automatiquement',
            ]);

            // Generate records for this flock
            $this->generatePoultryRecords($flock);
        }
    }

    private function generatePoultryRecords($flock)
    {
        $startDate = $flock->arrival_date;
        $endDate = now();
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            PoultryRecord::create([
                'flock_id' => $flock->id,
                'record_date' => $currentDate,
                'eggs_collected' => $flock->type === 'layer' ? rand(50, 200) : 0,
                'feed_consumed' => rand(20, 80),
                'mortality_count' => rand(0, 3),
                'average_weight' => rand(15, 25) / 10,
                'health_notes' => ['Bon état', 'Excellent', 'Normal'][rand(0, 2)],
                'recorded_by' => User::first()->id,
            ]);
            
            $currentDate->addDay();
        }
    }

    private function generateCattleData($count)
    {
        $zones = Zone::where('type', 'pasture')->get();
        
        if ($zones->isEmpty()) {
            $this->warn('No cattle zones found.');
            return;
        }

        for ($i = 1; $i <= $count; $i++) {
            $cattle = Cattle::create([
                'tag_number' => 'BOV-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => ['Belle', 'Luna', 'Star', 'Moon', 'Sun'][rand(0, 4)],
                'breed' => ['Holstein', 'Jersey', 'Simmental'][rand(0, 2)],
                'gender' => ['male', 'female'][rand(0, 1)],
                'birth_date' => Carbon::now()->subYears(rand(1, 8)),
                'current_weight' => rand(300, 800),
                'zone_id' => $zones->random()->id,
                'notes' => 'Animal de test généré automatiquement',
            ]);

            // Generate records for this cattle
            $this->generateCattleRecords($cattle);
        }
    }

    private function generateCattleRecords($cattle)
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = now();
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            CattleRecord::create([
                'cattle_id' => $cattle->id,
                'record_date' => $currentDate,
                'milk_production' => $cattle->gender === 'female' ? rand(15, 35) : 0,
                'weight' => $cattle->current_weight + rand(-5, 5),
                'health_status' => ['healthy', 'healthy', 'healthy', 'sick'][rand(0, 3)],
                'health_notes' => ['Excellent', 'Bon', 'Normal'][rand(0, 2)],
                'recorded_by' => User::first()->id,
            ]);
            
            $currentDate->addDay();
        }
    }

    private function generateCropData($count)
    {
        $zones = Zone::where('type', 'cultivation')->get();
        
        if ($zones->isEmpty()) {
            $this->warn('No crop zones found.');
            return;
        }

        $crops = ['Maïs', 'Blé', 'Riz', 'Tomate', 'Pomme de terre'];
        
        for ($i = 1; $i <= $count; $i++) {
            $crop = Crop::create([
                'name' => $crops[rand(0, 4)],
                'variety' => 'Variété ' . rand(1, 5),
                'zone_id' => $zones->random()->id,
                'planting_date' => Carbon::now()->subDays(rand(30, 120)),
                'expected_harvest_date' => Carbon::now()->addDays(rand(30, 90)),
                'planted_area' => rand(1000, 5000),
                'expected_yield' => rand(2000, 8000),
                'actual_yield' => rand(1500, 7500),
                'status' => ['planted', 'growing', 'harvested'][rand(0, 2)],
                'notes' => 'Culture de test générée automatiquement',
            ]);

            // Generate activities for this crop
            $this->generateCropActivities($crop);
        }
    }

    private function generateCropActivities($crop)
    {
        $activities = [
            ['type' => 'planting', 'description' => 'Plantation des semences'],
            ['type' => 'fertilizing', 'description' => 'Application d\'engrais'],
            ['type' => 'irrigation', 'description' => 'Irrigation de la parcelle'],
            ['type' => 'pest_control', 'description' => 'Traitement contre les ravageurs'],
        ];

        foreach ($activities as $activityData) {
            CropActivity::create([
                'crop_id' => $crop->id,
                'activity_type' => $activityData['type'],
                'activity_date' => $crop->planting_date->addDays(rand(1, 30)),
                'description' => $activityData['description'],
                'cost' => rand(50, 300),
                'performed_by' => User::first()->id,
            ]);
        }
    }

    private function generateTasks($count)
    {
        $zones = Zone::all();
        $users = User::all();
        
        if ($zones->isEmpty() || $users->isEmpty()) {
            $this->warn('No zones or users found for task generation.');
            return;
        }

        for ($i = 1; $i <= $count; $i++) {
            Task::create([
                'title' => 'Tâche ' . $i,
                'description' => 'Description de la tâche ' . $i,
                'type' => ['agricultural', 'livestock', 'maintenance', 'administrative'][rand(0, 3)],
                'priority' => ['low', 'medium', 'high', 'urgent'][rand(0, 3)],
                'status' => ['pending', 'in_progress', 'completed'][rand(0, 2)],
                'due_date' => Carbon::now()->addDays(rand(1, 30)),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'zone_id' => $zones->random()->id,
                'notes' => 'Tâche de test générée automatiquement',
            ]);
        }
    }
}
