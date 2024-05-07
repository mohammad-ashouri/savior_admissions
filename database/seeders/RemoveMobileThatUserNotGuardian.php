<?php

namespace Database\Seeders;

use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RemoveMobileThatUserNotGuardian extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentInformations = StudentInformation::distinct()->pluck('guardian')->toArray();

        User::whereHas('roles', function ($query) {
            $query->where('name', 'Parent');
        })
            ->whereNotIn('id', $studentInformations)
            ->update(['mobile' => null]);
    }
}
