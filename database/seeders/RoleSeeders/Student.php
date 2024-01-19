<?php

namespace Database\Seeders\RoleSeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Student extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::create(['name' => 'Student']);
        $studentRole->givePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);
    }
}
