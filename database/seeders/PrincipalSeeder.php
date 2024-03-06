<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PrincipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $user = User::query()->create([
//            'mobile' => '+989029966902',
//            'email' => 'test@savior.ir',
//            'password' => bcrypt(12345678),
//        ]);
//        $generalInformation = GeneralInformation::create(
//            [
//                'user_id' => $user->id,
//                'first_name_fa' => 'رضا',
//                'last_name_fa' => 'قنبری',
//                'first_name_en' => 'Reza',
//                'last_name_en' => 'Ghanbari',
//            ]
//        );
//        $role = Role::where('name', 'Principal')->first();
//        $user->assignRole([$role->id]);
    }
}
