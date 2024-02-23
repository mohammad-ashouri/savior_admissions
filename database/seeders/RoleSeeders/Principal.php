<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Principal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $principalRole = Role::create(['name' => 'Principal']);
        $principalRole->givePermissionTo([
            'branch-info-menu-access',
            'finance-menu-access',
            'users-menu-access',
            'branch-info-menu-access',
            'interviews-menu-access',
            'applications-menu-access',
            'academic-year-classes-menu-access',
            'application-timings-menu-access',
            'discounts-menu-access',
            'create-users',
            'show-user',
            'edit-users',
            'delete-users',
            'search-user',
            'list-users',
            'access-user-role',
            'change-student-information',
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
            'interview-list',
            'interview-set',
            'interview-edit',
            'interview-delete',
            'interview-search',
            'interview-show',
            'academic-year-class-list',
            'academic-year-class-create',
            'academic-year-class-edit',
            'academic-year-class-delete',
            'academic-year-class-search',
            'application-timing-list',
            'application-timing-create',
            'application-timing-edit',
            'application-timing-delete',
            'application-timing-search',
            'application-timing-show',
            'reservation-invoice-list',
            'reservation-invoice-create',
            'reservation-invoice-search',
            'reservation-invoice-edit',
            'reservation-invoice-show',
            'reservation-invoice-delete',
            'discounts-list',
            'discounts-create',
            'discounts-edit',
            'discounts-change-status',
            'discounts-show',
            'reservation-payment-details-show',
            'reservation-payment-status-change',
            'applications-list',
            'new-application-reserve',
            'show-application-reserve',
            'edit-application-reserve',
            'remove-application',
            'change-status-of-application',
            'remove-application-from-reserve',
        ]);
    }
}
