<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddConfirmInterviewForPrincipal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::createOrFirst(['name' => 'confirm-interview']);
        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'confirm-interview'
        ]);
        $principalRole = Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'confirm-interview'
        ]);
    }
}
