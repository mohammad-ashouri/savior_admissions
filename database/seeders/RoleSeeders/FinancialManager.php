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
            'reservation-invoice-details',
            'reservation-invoice-list',
            'reservation-invoice-search',
            'reservation-invoice-add',
            'reservation-invoice-edit',
            'reservation-invoice-search',
        ]);
    }
}
