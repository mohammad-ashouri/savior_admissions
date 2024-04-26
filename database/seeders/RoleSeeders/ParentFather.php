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
        $parentFatherRole = Role::create(['name' => 'Parent']);
        $parentFatherRole->givePermissionTo([
            'students-menu-access',
            'students-list',
            'students-create',
            'students-edit',
            'students-delete',
            'students-show',
            'reservation-payment-details-show',
            'applications-list',
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
