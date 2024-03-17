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
    }
}
