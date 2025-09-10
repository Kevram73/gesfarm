<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockCategory;

class StockCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Intrants agricoles
            ['name' => 'Semences', 'type' => 'agricultural_inputs', 'description' => 'Graines et semences pour les cultures'],
            ['name' => 'Engrais', 'type' => 'agricultural_inputs', 'description' => 'Engrais organiques et chimiques'],
            ['name' => 'Pesticides', 'type' => 'agricultural_inputs', 'description' => 'Produits phytosanitaires et insecticides'],
            ['name' => 'Herbicides', 'type' => 'agricultural_inputs', 'description' => 'Produits de désherbage'],
            
            // Aliments pour bétail
            ['name' => 'Aliments pour volailles', 'type' => 'animal_feed', 'description' => 'Granulés et aliments spécialisés pour volailles'],
            ['name' => 'Aliments pour bovins', 'type' => 'animal_feed', 'description' => 'Fourrages et concentrés pour bovins'],
            ['name' => 'Aliments pour ovins', 'type' => 'animal_feed', 'description' => 'Fourrages et concentrés pour ovins'],
            ['name' => 'Fourrages', 'type' => 'animal_feed', 'description' => 'Foin, ensilage et autres fourrages'],
            
            // Équipements
            ['name' => 'Outillage agricole', 'type' => 'equipment', 'description' => 'Outils manuels et petits équipements'],
            ['name' => 'Pièces détachées', 'type' => 'equipment', 'description' => 'Pièces de rechange pour machines'],
            ['name' => 'Matériel d\'élevage', 'type' => 'equipment', 'description' => 'Abreuvoirs, mangeoires, clôtures'],
            ['name' => 'Matériel d\'irrigation', 'type' => 'equipment', 'description' => 'Tuyaux, arroseurs, pompes'],
            
            // Produits vétérinaires
            ['name' => 'Médicaments', 'type' => 'veterinary_products', 'description' => 'Médicaments vétérinaires'],
            ['name' => 'Vaccins', 'type' => 'veterinary_products', 'description' => 'Vaccins pour animaux'],
            ['name' => 'Désinfectants', 'type' => 'veterinary_products', 'description' => 'Produits de désinfection'],
            ['name' => 'Suppléments nutritionnels', 'type' => 'veterinary_products', 'description' => 'Vitamines et suppléments'],
        ];

        foreach ($categories as $category) {
            StockCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
