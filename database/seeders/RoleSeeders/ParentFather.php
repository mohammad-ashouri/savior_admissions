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
            'students-menu-access',
            'students-list',
            'students-create',
            'students-edit',
            'students-delete',
            'students-search',
            'students-show',
            'reservation-payment-details-show',
            'applications-list',
            'new-application-reserve',
            'show-application-reserve',
            'applications-menu-access',
        ]);
    }
}
