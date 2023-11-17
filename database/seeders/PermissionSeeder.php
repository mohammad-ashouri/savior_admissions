<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create user', 'read user', 'update user', 'delete user',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];
        $permissions = collect($permissions)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });
        Permission::insert($permissions->toArray());
    }
}
