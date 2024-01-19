<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PrincipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::query()->create([
            'name'=>'Reza',
            'family'=>'Ghanbari',
            'mobile'=>'+989029966902',
            'email'=>'test@savior.ir',
            'password'=>bcrypt(12345678)
        ]);
        $generalInformation=GeneralInformation::create(
            [
                'user_id'=>$user->id
            ]
        );
        $role = Role::where('name','Principal')->first();
        $user->assignRole([$role->id]);
    }
}
