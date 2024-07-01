<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RevokeDocumentPermissionFromParentRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the role instance
        $role = Role::findByName('Parent');

// Revoke a specific permission from the role
        $role->revokePermissionTo([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
        ]);

    }
}
