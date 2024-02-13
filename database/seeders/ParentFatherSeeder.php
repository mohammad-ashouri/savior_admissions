<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ParentFatherSeeder extends Seeder
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
            'mobile' => '+989398888226',
            'email' => 'test@gmail.com',
            'password' => bcrypt(12345678),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'علی',
                'last_name_fa' => 'والدین(پدر)',
                'first_name_en' => 'Ali',
                'last_name_en' => 'Father',
            ]
        );
        $role = Role::where('name', 'Parent(Father)')->first();
        $user->assignRole([$role->id]);
    }
}
