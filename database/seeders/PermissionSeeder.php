<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

//        $permissions = [
//            'create user', 'read user', 'update user', 'delete user',
//            'role-list',
//            'role-create',
//            'role-edit',
//            'role-delete',
//        ];
//        $permissions = collect($permissions)->map(function ($permission) {
//            return ['name' => $permission, 'guard_name' => 'web'];
//        });
//        Permission::insert($permissions->toArray());

        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'read user']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'role-list']);
        Permission::create(['name' => 'role-create']);
        Permission::create(['name' => 'role-edit']);
        Permission::create(['name' => 'role-delete']);

        Permission::create(['name' => 'document-list']);
        Permission::create(['name' => 'document-create']);
        Permission::create(['name' => 'document-edit']);
        Permission::create(['name' => 'document-delete']);

        $adminRole = Role::create(['name' => 'Admin']);
        $parentRole = Role::create(['name' => 'Parent']);

        $adminRole->givePermissionTo([
            'create-users',
            'read user',
            'edit-users',
            'delete-users',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);

        $parentRole->givePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);
    }
}