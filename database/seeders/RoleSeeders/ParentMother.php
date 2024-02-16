<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ParentMother extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentMotherRole = Role::create(['name' => 'Parent(Mother)']);
        $parentMotherRole->givePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
            'students-menu-access',
            'students-list',
            'students-create',
            'students-edit',
            'students-delete',
            'students-search',
            'students-show',
            'applications-list',
            'new-application-reserve',
            'show-application-reserve',
            'applications-menu-access',
        ]);
    }
}
