<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RemoveAllStudentsMobiles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereHas('roles', function ($query) {
            $query->where('name', 'Student');
        })
            ->update([
                'mobile' => null,
            ]);
    }
}
