<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Interviewer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interviewerRole = Role::create(['name' => 'Interviewer']);
        $interviewerRole->givePermissionTo([
            'branch-info-menu-access',
            'interviews-menu-access',
            'interview-list',
            'interview-set',
            'interview-edit',
            'interview-delete',
            'interview-search',
            'interview-show',
        ]);
    }
}
