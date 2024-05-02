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
        User::where('mobile', 'LIKE', '09%')
            ->orWhere('mobile', 'LIKE', '0%')
            ->update([
                'mobile' => DB::raw("CASE WHEN LEFT(mobile, 2) = '09' THEN CONCAT('+98', SUBSTRING(mobile, 3)) ELSE CONCAT('+', SUBSTRING(mobile, 2)) END"),
            ]);
    }
}
