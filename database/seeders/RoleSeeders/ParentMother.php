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
            'childes-menu-access',
            'childes-list',
            'childes-create',
            'childes-edit',
            'childes-delete',
            'childes-search',
            'childes-show',
            'applications-list',
            'new-application-reserve',
            'show-application-reserve',
            'applications-menu-access',
        ]);
    }
}
