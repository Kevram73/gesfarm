<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Stock management
            'view-stock',
            'create-stock',
            'edit-stock',
            'delete-stock',
            'manage-stock-movements',
            
            // Poultry management
            'view-poultry',
            'create-poultry',
            'edit-poultry',
            'delete-poultry',
            'record-poultry-data',
            'manage-incubation',
            
            // Cattle management
            'view-cattle',
            'create-cattle',
            'edit-cattle',
            'delete-cattle',
            'record-cattle-data',
            
            // Crop management
            'view-crops',
            'create-crops',
            'edit-crops',
            'delete-crops',
            'record-crop-activities',
            
            // Zone management
            'view-zones',
            'create-zones',
            'edit-zones',
            'delete-zones',
            
            // Dashboard
            'view-dashboard',
            'view-reports',
            
            // Tasks
            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'assign-tasks',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $veterinarianRole = Role::firstOrCreate(['name' => 'veterinarian']);
        $veterinarianRole->givePermissionTo([
            'view-poultry',
            'create-poultry',
            'edit-poultry',
            'record-poultry-data',
            'manage-incubation',
            'view-cattle',
            'create-cattle',
            'edit-cattle',
            'record-cattle-data',
            'view-stock',
            'view-dashboard',
            'view-tasks',
            'create-tasks',
            'edit-tasks',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view-stock',
            'create-stock',
            'edit-stock',
            'manage-stock-movements',
            'view-poultry',
            'view-cattle',
            'view-crops',
            'create-crops',
            'edit-crops',
            'record-crop-activities',
            'view-zones',
            'create-zones',
            'edit-zones',
            'view-dashboard',
            'view-reports',
            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'assign-tasks',
        ]);

        $workerRole = Role::firstOrCreate(['name' => 'worker']);
        $workerRole->givePermissionTo([
            'view-stock',
            'view-poultry',
            'record-poultry-data',
            'view-cattle',
            'record-cattle-data',
            'view-crops',
            'record-crop-activities',
            'view-zones',
            'view-tasks',
            'edit-tasks',
        ]);

        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisorRole->givePermissionTo([
            'view-stock',
            'create-stock',
            'edit-stock',
            'manage-stock-movements',
            'view-poultry',
            'create-poultry',
            'edit-poultry',
            'record-poultry-data',
            'view-cattle',
            'create-cattle',
            'edit-cattle',
            'record-cattle-data',
            'view-crops',
            'create-crops',
            'edit-crops',
            'record-crop-activities',
            'view-zones',
            'create-zones',
            'edit-zones',
            'view-dashboard',
            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'assign-tasks',
        ]);
    }
}
