<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class FinancialManager extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financialManagerRole = Role::create(['name' => 'Financial Manager']);
        $financialManagerRole->givePermissionTo([
            'finance-menu-access',
            'discounts-menu-access',
            'tuition-menu-access',
            'reservation-invoice-list',
            'reservation-invoice-create',
            'reservation-invoice-edit',
            'reservation-invoice-search',
            'reservation-invoice-show',
            'reservation-invoice-delete',
            'reservation-payment-details-show',
            'reservation-payment-status-change',
            'discounts-list',
            'discounts-create',
            'discounts-edit',
            'discounts-change-status',
            'discounts-show',
            'tuition-list',
            'tuition-create',
            'tuition-edit',
            'tuition-change-status',
            'tuition-show',
        ]);
    }
}
