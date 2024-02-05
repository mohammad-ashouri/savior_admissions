<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ParentFather extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parentFatherRole = Role::create(['name' => 'Parent(Father)']);
        $parentFatherRole->givePermissionTo([
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
        ]);
    }
}
