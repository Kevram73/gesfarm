<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la ferme unique
        $farm = Farm::first();

        // Vérifier si l'utilisateur admin existe déjà
        $adminExists = User::where('email', 'admin@farm-manager.com')->exists();

        if (!$adminExists) {
            $admin = User::create([
                'name' => 'Administrateur',
                'fullname' => 'Administrateur Farm Manager',
                'email' => 'admin@farm-manager.com',
                'password' => Hash::make('admin123'), // Mot de passe par défaut à changer
                'role' => 'SUPER_ADMIN',
                'farm_id' => $farm ? $farm->id : null,
                'phone' => null,
                'phone_number' => null,
                'address' => null,
                'status' => true,
                'is_active' => true,
                'email_verified' => now(),
            ]);

            // Mettre à jour la ferme avec l'admin comme manager
            if ($farm && !$farm->manager_id) {
                $farm->update(['manager_id' => $admin->id]);
            }

            $this->command->info('Utilisateur administrateur créé avec succès !');
            $this->command->warn('Email: admin@farm-manager.com');
            $this->command->warn('Mot de passe: admin123');
            $this->command->warn('⚠️  IMPORTANT: Changez le mot de passe après la première connexion !');
        } else {
            $this->command->info('L\'utilisateur administrateur existe déjà.');
        }
    }
}
