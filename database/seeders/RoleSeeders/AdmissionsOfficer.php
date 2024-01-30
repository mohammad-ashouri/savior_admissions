<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdmissionsOfficer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admissionsOfficerRole = Role::create(['name' => 'Admissions Officer']);
        $admissionsOfficerRole->givePermissionTo([
            'interview-list',
            'interview-set',
            'interview-edit',
            'interview-delete',
            'interview-search',
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
        ]);
    }
}
