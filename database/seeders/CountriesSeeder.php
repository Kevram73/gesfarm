<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Togo', 'code' => 'TG'],
            ['name' => 'Bénin', 'code' => 'BJ'],
            ['name' => 'Côte d\'Ivoire', 'code' => 'CI'],
            ['name' => 'Burkina Faso', 'code' => 'BF'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['code' => $country['code']],
                ['name' => $country['name']]
            );
        }

        $this->command->info('Pays créés avec succès !');
        $this->command->info('Pays ajoutés : ' . implode(', ', array_column($countries, 'name')));
    }
}
