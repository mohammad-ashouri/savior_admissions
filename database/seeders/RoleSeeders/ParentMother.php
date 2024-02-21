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
            'reservation-payment-details-show',
            'new-application-reserve',
            'show-application-reserve',
            'interview-list',
            'interview-search',
            'interview-show',
            'applications-menu-access',
            'branch-info-menu-access',
            'interviews-menu-access',
        ]);
    }
}
