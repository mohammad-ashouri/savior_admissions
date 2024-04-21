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
            'mobile' => '+988122099434',
            'email' => 'f.naseri@saviorschools.com',
            'password' => bcrypt('naseri6342'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'فرنوش',
                'last_name_fa' => 'ناصری',
                'first_name_en' => 'Farnoush',
                'last_name_en' => 'Naseri',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Admissions Officer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989360630908',
            'email' => 'm.khoshdel@saviorschools.com',
            'password' => bcrypt('khoshdel9865'),
            'status' => 1,
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'محمدرضا',
                'last_name_fa' => 'خوشدل',
                'first_name_en' => 'Mohammad Reza',
                'last_name_en' => 'Khoshdel',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Admissions Officer')->first();
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
        $role = Role::where('name', 'Admissions Officer')->first();
        $user->assignRole([$role->id]);
    }
}
