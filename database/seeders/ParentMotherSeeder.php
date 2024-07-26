<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ParentMotherSeeder extends Seeder
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
            'mobile' => '+989398844226',
            'email' => 'test34@gmail.com',
            'password' => bcrypt(12345678),
        ]);
        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id,
                'first_name_fa' => 'فاطمه',
                'last_name_fa' => 'والدین(مادر)',
                'first_name_en' => 'Fatima',
                'last_name_en' => 'Mother',
            ]
        );
        $role = Role::whereName('Parent')->first();
        $user->assignRole([$role->id]);
    }
}
