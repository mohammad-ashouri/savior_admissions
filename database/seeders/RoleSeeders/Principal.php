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
            'users-menu-access',
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
            'list-of-application-interviews',
            'remove-interview-from-reserve',
            'change-status-of-interview',
            'remove-interview',
        ]);
    }
}
