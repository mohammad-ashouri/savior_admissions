<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class InterviewerSeeder extends Seeder
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
            'mobile' => '+989122520482',
            'email' => 'n.daraei@saviorschools.com',
            'password' => bcrypt('daraei4364'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'نسرین',
                'last_name_fa' => 'دارایی',
                'first_name_en' => 'Nasrin',
                'last_name_en' => 'Daraei',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989101757902',
            'email' => 'y.ghosi@saviorschools.com',
            'password' => bcrypt('ghosi7453'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'یگانه',
                'last_name_fa' => 'قوسی',
                'first_name_en' => 'Yeganeh',
                'last_name_en' => 'Ghosi',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989370705568',
            'email' => 's.albattat@saviorschools.com',
            'password' => bcrypt('albattat4579'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'سکینه',
                'last_name_fa' => 'البطاط',
                'first_name_en' => 'Sakinah',
                'last_name_en' => 'Albattat',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989196523437',
            'email' => 'n.abyar@saviorschools.com',
            'password' => bcrypt('abyar6342'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'نجمه',
                'last_name_fa' => 'آبیار',
                'first_name_en' => 'Najmeh',
                'last_name_en' => 'Abyar',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989191593910',
            'email' => 't.rahimi@saviorschools.com',
            'password' => bcrypt('rahimi1568'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'طاهره',
                'last_name_fa' => 'رحیمی',
                'first_name_en' => 'Tahereh',
                'last_name_en' => 'Rahimi',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989368541933',
            'email' => 'p.balali@saviorschools.com',
            'password' => bcrypt('balali9810'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'پیمان',
                'last_name_fa' => 'بلالی',
                'first_name_en' => 'Peyman',
                'last_name_en' => 'Balali',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989368541933',
            'email' => 'm.bahrani@saviorschools.com',
            'password' => bcrypt('bahrani4568'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'محمدعلی',
                'last_name_fa' => 'بحرانی',
                'first_name_en' => 'Mohammad Ali',
                'last_name_en' => 'Bahrani',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);

        $user = User::query()->create([
            'mobile' => '+989191591374',
            'email' => 'a.s.hasani@saviorschools.com',
            'password' => bcrypt('hasani4236'),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'علیرضا',
                'last_name_fa' => 'سراجه حسنی',
                'first_name_en' => 'Alireza',
                'last_name_en' => 'Seraje Hasani',
                'status' => 1,
            ]
        );
        $role = Role::where('name', 'Interviewer')->first();
        $user->assignRole([$role->id]);
    }
}
