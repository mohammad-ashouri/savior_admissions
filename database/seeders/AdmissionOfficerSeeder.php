<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdmissionOfficerSeeder extends Seeder
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
            'mobile' => '+989152465487',
            'email' => 'test@sa.com',
            'password' => bcrypt(12345678),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'حمید',
                'last_name_fa' => 'مدیر آموزش',
                'first_name_en' => 'Hamid',
                'last_name_en' => 'AdmissionOfficer',
            ]
        );
        $role = Role::where('name', 'Admissions Officer')->first();
        $user->assignRole([$role->id]);
    }
}
