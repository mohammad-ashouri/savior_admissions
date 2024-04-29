<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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
            'applications-list',
            'new-application-reserve',
            'show-application-reserve',
            'edit-application-reserve',
            'remove-application',
            'change-status-of-application',
            'remove-application-from-reserve',
            'branch-info-menu-access',
            'applications-menu-access',
            'academic-year-classes-menu-access',
            'application-timings-menu-access',
            'students-menu-access',
            'interviews-menu-access',
            'students-list',
            'students-edit',
            'students-delete',
            'students-search',
            'students-show',
            'evidences-confirmation',
            'student-statuses-menu-access',
            'student-statuses-list',
        ]);
    }
}
