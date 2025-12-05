<?php

namespace Database\Seeders;

use App\Models\SelectOption;
use App\Helpers\FarmHelper;
use Illuminate\Database\Seeder;

class SoilTypeAndClimateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utiliser null pour les options globales (disponibles pour toutes les fermes)
        $farmId = null;
        
        // Si une ferme existe, on peut l'utiliser
        try {
            $farmId = FarmHelper::getFarmId();
        } catch (\Exception $e) {
            // Si la ferme n'existe pas encore, utiliser null pour les options globales
            $farmId = null;
        }

        // Types de sol
        $soilTypes = [
            ['value' => 'sandy', 'label' => 'Sableux', 'order' => 1],
            ['value' => 'clay', 'label' => 'Argileux', 'order' => 2],
            ['value' => 'loamy', 'label' => 'Limon', 'order' => 3],
            ['value' => 'silty', 'label' => 'Limoneux', 'order' => 4],
            ['value' => 'peaty', 'label' => 'Tourbeux', 'order' => 5],
            ['value' => 'chalky', 'label' => 'Calcaire', 'order' => 6],
            ['value' => 'rocky', 'label' => 'Rocheux', 'order' => 7],
            ['value' => 'volcanic', 'label' => 'Volcanique', 'order' => 8],
        ];

        foreach ($soilTypes as $soilType) {
            SelectOption::firstOrCreate(
                [
                    'farm_id' => $farmId,
                    'category' => 'soilType',
                    'value' => $soilType['value'],
                ],
                [
                    'label' => $soilType['label'],
                    'order' => $soilType['order'],
                    'is_active' => true,
                ]
            );
        }

        // Climats
        $climates = [
            ['value' => 'tropical', 'label' => 'Tropical', 'order' => 1],
            ['value' => 'subtropical', 'label' => 'Subtropical', 'order' => 2],
            ['value' => 'temperate', 'label' => 'Tempéré', 'order' => 3],
            ['value' => 'continental', 'label' => 'Continental', 'order' => 4],
            ['value' => 'mediterranean', 'label' => 'Méditerranéen', 'order' => 5],
            ['value' => 'arid', 'label' => 'Aride', 'order' => 6],
            ['value' => 'semi-arid', 'label' => 'Semi-aride', 'order' => 7],
            ['value' => 'humid', 'label' => 'Humide', 'order' => 8],
            ['value' => 'subarctic', 'label' => 'Subarctique', 'order' => 9],
        ];

        foreach ($climates as $climate) {
            SelectOption::firstOrCreate(
                [
                    'farm_id' => $farmId,
                    'category' => 'climate',
                    'value' => $climate['value'],
                ],
                [
                    'label' => $climate['label'],
                    'order' => $climate['order'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Types de sol et climats créés avec succès !');
    }
}
