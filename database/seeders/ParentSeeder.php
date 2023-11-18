<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ParentSeeder extends Seeder
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
            'name'=>'Ali',
            'family'=>'Karimi',
            'mobile'=>'+989398888226',
            'email'=>'test@gmail.com',
            'password'=>bcrypt(12345678)
        ]);
        $role = Role::where('name','parent')->first();
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
