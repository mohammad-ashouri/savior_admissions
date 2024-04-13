<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class FinancialManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var User $user
         */
        $user = User::query()->create([
            'mobile' => '+989022222222',
            'email' => 'n.ahamadi@saviorschools.com',
            'password' => bcrypt('ahmadi444'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'نسا',
                'last_name_fa' => 'احمدی',
                'first_name_en' => 'Nesa',
                'last_name_en' => 'Ahmadi',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Financial Manager')->first();
        $user->assignRole([$role->id]);


        $user = User::query()->create([
            'mobile' => '+989033333333',
            'email' => 'r.ahmadi@saviorschools.com',
            'password' => bcrypt('ahmadi1314'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'رضا',
                'last_name_fa' => 'احمدی',
                'first_name_en' => 'Reza',
                'last_name_en' => 'Ahmadi',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Financial Manager')->first();
        $user->assignRole([$role->id]);


        $user = User::query()->create([
            'mobile' => '+989109707302',
            'email' => 'f.najari@saviorschooslc.om',
            'password' => bcrypt('najari574'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'فاطمه',
                'last_name_fa' => 'نجاری',
                'first_name_en' => 'Fatemeh',
                'last_name_en' => 'Najari',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Financial Manager')->first();
        $user->assignRole([$role->id]);
    }
}
