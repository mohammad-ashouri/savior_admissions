<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChangeUsersMobileFormatToInternational extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereHas('roles', function ($query) {
                $query->where('name', 'Student')->orWhere('name', 'Parent');
            })
            ->update([
                'mobile' => DB::raw("CASE
                                WHEN LEFT(mobile, 3) = '009' THEN CONCAT('+98', SUBSTRING(mobile, 4))
                                WHEN LEFT(mobile, 2) = '00' THEN CONCAT('+', SUBSTRING(mobile, 2))
                                WHEN LEFT(mobile, 1) = '0' THEN CONCAT('+98', SUBSTRING(mobile, 2))
                                ELSE CONCAT('+', REPLACE(mobile, '+', ''))
                            END"),
            ]);

    }
}
