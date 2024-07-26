<?php

namespace Database\Seeders\TemporarySeeders;

use App\Models\StudentInformation;
use App\Models\User;
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
            $query->whereName('Parent');
        })
            ->whereNotIn('id', $studentInformations)
            ->update(['mobile' => null]);
    }
}
