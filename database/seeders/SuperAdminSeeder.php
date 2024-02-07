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
                'first_name' => 'Mohammad',
                'last_name' => 'Ashouri',
            ]
        );
        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);
    }
}
