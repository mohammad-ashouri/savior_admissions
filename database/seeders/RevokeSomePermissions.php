<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RevokeSomePermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'education-type-list',
            'education-type-create',
            'education-type-edit',
            'education-type-delete',
            'education-type-search',
            'level-list',
            'level-create',
            'level-edit',
            'level-delete',
            'level-search',
            'students-list',
            'students-create',
            'students-edit',
            'students-delete',
            'students-search',
            'students-show',
            'students-menu-access',
        ];

        foreach ($permissions as $permission) {
            $rolesWithPermission = Role::where('name','!=','Parent')->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })->get();

            foreach ($rolesWithPermission as $role) {
                $role->revokePermissionTo($permission);
            }
        }
    }
}
