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
        Permission::create(['name' => 'read-user']);
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

        Permission::create(['name' => 'catalogs-list']);
        Permission::create(['name' => 'catalogs-create']);
        Permission::create(['name' => 'catalogs-edit']);
        Permission::create(['name' => 'catalogs-delete']);

        Permission::create(['name' => 'interview-list']);
        Permission::create(['name' => 'interview-set']);
        Permission::create(['name' => 'interview-edit']);
        Permission::create(['name' => 'interview-delete']);

        $superAdminRole = Role::create(['name' => 'SuperAdmin']);
        $schoolAdminRole = Role::create(['name' => 'SchoolAdmin']);
        $parentFatherRole = Role::create(['name' => 'Parent(Father)']);
        $parentMotherRole = Role::create(['name' => 'Parent(Mother)']);
        $studentRole = Role::create(['name' => 'Student']);
        $interviewerRole = Role::create(['name' => 'Interviewer']);

        $superAdminRole->givePermissionTo([
            'create-users',
            'read-user',
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
            'catalogs-list',
            'catalogs-create',
            'catalogs-edit',
            'catalogs-delete',
            'interview-list',
            'interview-set',
            'interview-edit',
            'interview-delete',
        ]);

        $schoolAdminRole->givePermissionTo([
            'create-users',
            'read-user',
            'edit-users',
            'delete-users',
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);

        $parentFatherRole->givePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);

        $parentMotherRole->givePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);

        $studentRole->givePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);

        $interviewerRole->givePermissionTo([
            'interview-list',
            'interview-set'
        ]);
    }
}
