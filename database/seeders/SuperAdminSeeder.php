<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var User $user
         */
//        $user = User::query()->create([
//            'mobile' => '+989012682581',
//            'email' => 'test@example.com',
//            'password' => bcrypt(12345678),
//        ]);
//        $generalInformation = GeneralInformation::create(
//            [
//                'user_id' => $user->id,
//                'first_name_fa' => 'محمد',
//                'last_name_fa' => 'عاشوری',
//                'first_name_en' => 'Mohammad',
//                'last_name_en' => 'Ashouri',
//            ]
//        );
//        $role = Role::where('name', 'Super Admin')->first();
//        $user->assignRole([$role->id]);
//
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
//        $role = Role::where('name', 'Super Admin')->first();
//        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989011111111',
            'email' => 'a.alavian@saviorschools.com',
            'password' => bcrypt('alavian1247'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'علی',
                'last_name_fa' => 'علویان',
                'first_name_en' => 'Ali',
                'last_name_en' => 'Alavian',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989107542570',
            'email' => 'm.jalilian@saviorschools.com',
            'password' => bcrypt('jalilian9475'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'محمود',
                'last_name_fa' => 'جلیلیان',
                'first_name_en' => 'Mahmood',
                'last_name_en' => 'Jalilian',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989102044120',
            'email' => 'r.ghanavati@saviorschools.com',
            'password' => bcrypt('ghanavati9475'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'رضوان',
                'last_name_fa' => 'قنواتی',
                'first_name_en' => 'Rezvan',
                'last_name_en' => 'Ghanavati',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);
    }
}
