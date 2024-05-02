<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeleteParentsAndStudentsEmail extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereHas('roles', function($query) {
            $query->where('name', 'Student')->orWhere('name', 'Parent');
        })->update([
            'email'=>null
        ]);
    }
}
