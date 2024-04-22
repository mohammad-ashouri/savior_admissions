<?php

namespace Database\Seeders;

use App\Models\GeneralInformation;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IDChanger extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all users whose role is not "student"
        $usersToUpdate = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'student');
        })->get();

        // Increment the IDs of users by 100,000
        foreach ($usersToUpdate as $user) {
            $generalInformationUserIDs=GeneralInformation::where('user_id', $user->id)->first();
            $lastUserID=$user->id;
            $user->id += 100000;
            $studentInformationsParentFather=StudentInformation::where('parent_father_id', $lastUserID)->update([
                'parent_father_id' => $user->id,
            ]);
            $studentInformationsParentMother=StudentInformation::where('parent_mother_id',$lastUserID)->update([
                'parent_mother_id' => $user->id,
            ]);
            $studentInformationsGuardian=StudentInformation::where('guardian',$lastUserID)->update([
                'guardian' => $user->id,
            ]);
            $roles=DB::table('model_has_roles')->where('model_id',$lastUserID)->update([
                'model_id' => $user->id,
            ]);
            $generalInformationUserIDs->user_id=$user->id;
            $user->save();
            $generalInformationUserIDs->save();
        }
    }
}
