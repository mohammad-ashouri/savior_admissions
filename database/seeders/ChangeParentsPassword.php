<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangeParentsPassword extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereHas('roles', function ($query) {
            $query->where('name', 'Parent');
        })->update([
            'password' => DB::raw("SUBSTRING(mobile, -4)")
        ]);

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Parent');
        })->get();

        foreach ($users as $user) {
            $parent = User::find($user->id);
            $parent->password = Hash::make(substr($parent->mobile, -4));
            $parent->save();
        }
    }
}
