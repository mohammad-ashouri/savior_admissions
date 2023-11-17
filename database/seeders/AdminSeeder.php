<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
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
            'name'=>'Mohammad',
            'family'=>'Ashouri',
            'mobile'=>'+989012682581',
            'email'=>'test@example.com',
            'password'=>bcrypt(12345678)
        ]);

        $permission=Permission::query()->pluck('name')->toArray();
        $user->givePermissionTo($permission);
    }
}
