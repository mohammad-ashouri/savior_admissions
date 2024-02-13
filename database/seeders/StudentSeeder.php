<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->create([
            'mobile' => '+989024567894',
            'email' => 'test@savior1.ir',
            'password' => bcrypt(12345678),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'اکبر',
                'last_name_fa' => 'دانش آموز',
                'first_name_en' => 'Akbar',
                'last_name_en' => 'Student',
            ]
        );
        $role = Role::where('name', 'Student')->first();
        $user->assignRole([$role->id]);
    }
}
