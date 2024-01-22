<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class FinancialManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * @var User $user
         */
        $user=User::query()->create([
            'name'=>'Amir',
            'family'=>'Finance',
            'mobile'=>'+989026548798',
            'email'=>'test@testi.com',
            'password'=>bcrypt(12345678)
        ]);
        $generalInformation=GeneralInformation::create(
            [
                'user_id'=>$user->id
            ]
        );
        $role = Role::where('name','Financial Manager')->first();
        $user->assignRole([$role->id]);
    }
}
