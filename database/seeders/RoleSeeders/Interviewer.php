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
            'interview-list',
            'interview-set',
            'interview-search',
        ]);
    }
}
