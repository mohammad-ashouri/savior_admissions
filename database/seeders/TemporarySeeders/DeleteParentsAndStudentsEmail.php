<?php

namespace Database\Seeders\TemporarySeeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DeleteParentsAndStudentsEmail extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereHas('roles', function($query) {
            $query->whereName('Student')->orWhere('name', 'Parent');
        })->update([
            'email'=>null
        ]);
    }
}
