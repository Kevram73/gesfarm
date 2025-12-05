<?php

namespace Database\Seeders;

use App\Models\Farm;
use Illuminate\Database\Seeder;

class FarmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si une ferme existe déjà
        $farmExists = Farm::exists();

        if (!$farmExists) {
            $farm = Farm::create([
                'name' => 'Ma Ferme',
                'address' => '',
                'phone' => '',
                'email' => '',
                'country_id' => null,
                'city' => '',
                'is_active' => true,
                'code' => 'FARM001',
                'manager_id' => null,
                'settings' => [],
                'total_area' => 0,
                'cultivated_area' => 0,
                'soil_type' => null,
                'climate' => null,
            ]);

            $this->command->info('Ferme créée avec succès !');
            $this->command->info('Nom: ' . $farm->name);
        } else {
            $this->command->info('Une ferme existe déjà.');
        }
    }
}
