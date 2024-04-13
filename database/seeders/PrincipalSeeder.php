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
        $user = User::query()->create([
            'mobile' => '+989055555555',
            'email' => 'm.shafiee@saviorschools.com',
            'password' => bcrypt('sahfiee1461'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'محمدحسین',
                'last_name_fa' => 'شفیعی',
                'first_name_en' => 'Mohammad Hossain',
                'last_name_en' => 'Shafiee',
            ]
        );
        $role = Role::where('name', 'Principal')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989014995225',
            'email' => 'z.hosseini@saviorschools.com',
            'password' => bcrypt('hosseini2432'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'زهرا',
                'last_name_fa' => 'حسینی',
                'first_name_en' => 'Zahra',
                'last_name_en' => 'Hosseini',
            ]
        );
        $role = Role::where('name', 'Principal')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989125530554',
            'email' => 'a.pejhman@saviorschools.com',
            'password' => bcrypt('pejhman6231'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'افسانه',
                'last_name_fa' => 'پژمان',
                'first_name_en' => 'Afsaneh',
                'last_name_en' => 'Pejhman',
            ]
        );
        $role = Role::where('name', 'Principal')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989373667166',
            'email' => 'm.shafiee@saviorschools.com',
            'password' => bcrypt('shafiee0654'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'محمدحسین',
                'last_name_fa' => 'شفیعی',
                'first_name_en' => 'Mohammad Hossein',
                'last_name_en' => 'Shafiee',
            ]
        );
        $role = Role::where('name', 'Principal')->first();
        $user->assignRole([$role->id]);
    }
}
