<?php

namespace App\Helpers;

use App\Models\Farm;
use Illuminate\Support\Facades\Cache;

class FarmHelper
{
    /**
     * Obtenir la ferme unique de l'application
     * 
     * @return Farm
     */
    public static function getFarm(): Farm
    {
        return Cache::remember('single_farm', 3600, function () {
            $farm = Farm::first();
            
            // Si aucune ferme n'existe, créer une ferme par défaut
            if (!$farm) {
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
                
                // Réinitialiser le cache après création
                self::clearCache();
            }
            
            return $farm;
        });
    }

    /**
     * Obtenir l'ID de la ferme unique
     * 
     * @return int
     */
    public static function getFarmId(): int
    {
        return self::getFarm()->id;
    }

    /**
     * Réinitialiser le cache de la ferme
     */
    public static function clearCache(): void
    {
        Cache::forget('single_farm');
    }
}

