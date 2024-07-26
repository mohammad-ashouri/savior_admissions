<?php

namespace Database\Seeders\TemporarySeeders;

use App\Models\Document;
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
        $usersToUpdate = User::whereDoesntHave('roles', function ($query) {
            $query->whereName('student');
        })->where('id','!=',1)->where('id','!=',2)->get();

        foreach ($usersToUpdate as $user) {
            // Store the current user ID
            $oldUserID = $user->id;

            // Increment the user ID by 100,000
            $user->id += 100000;

            // Save the updated user record
            $user->save();

            // Update the related records in other tables
            GeneralInformation::whereUserId($oldUserID)->update(['user_id' => $user->id]);

            // Update parent_father_id in StudentInformation table
            StudentInformation::where('parent_father_id', $oldUserID)->update(['parent_father_id' => $user->id]);

            // Update parent_mother_id in StudentInformation table
            StudentInformation::where('parent_mother_id', $oldUserID)->update(['parent_mother_id' => $user->id]);

            // Update guardian in StudentInformation table
            StudentInformation::where('guardian', $oldUserID)->update(['guardian' => $user->id]);

            // Update user_id in Documents table
            Document::whereUserId($oldUserID)->update(['user_id' => $user->id]);

            // Update model_id in model_has_roles table
            DB::table('model_has_roles')->where('model_id', $oldUserID)->update(['model_id' => $user->id]);
        }

    }
}
