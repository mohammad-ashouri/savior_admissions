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
        $user = User::query()->create([
            'mobile' => '+989012682581',
            'email' => 'test@example.com',
            'password' => bcrypt(12345678),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'محمد',
                'last_name_fa' => 'عاشوری',
                'first_name_en' => 'Mohammad',
                'last_name_en' => 'Ashouri',
            ]
        );
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989029966902',
            'email' => 'test@savior.ir',
            'password' => bcrypt(12345678),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'رضا',
                'last_name_fa' => 'قنبری',
                'first_name_en' => 'Reza',
                'last_name_en' => 'Ghanbari',
            ]
        );
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);
    }
}
