<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions
        $this->call([
            RoleSeeder::class,
            StockCategorySeeder::class,
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gesfarm.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create veterinarian user
        $vet = User::firstOrCreate(
            ['email' => 'vet@gesfarm.com'],
            [
                'name' => 'Vétérinaire',
                'password' => Hash::make('password'),
            ]
        );
        $vet->assignRole('veterinarian');

        // Create manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@gesfarm.com'],
            [
                'name' => 'Gestionnaire',
                'password' => Hash::make('password'),
            ]
        );
        $manager->assignRole('manager');

        // Create worker user
        $worker = User::firstOrCreate(
            ['email' => 'worker@gesfarm.com'],
            [
                'name' => 'Ouvrier',
                'password' => Hash::make('password'),
            ]
        );
        $worker->assignRole('worker');
    }
}
