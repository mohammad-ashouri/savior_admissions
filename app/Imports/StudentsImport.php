<?php
namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;
use App\Models\GeneralInformation;

class StudentsImport implements ToModel
{
    public function model(array $row)
    {

        // Define how each row in the Excel file should be mapped to your database model
        $user= new User([
            'id' => $row[0],
            'mobile' => $row[9],
            'email' => $row[12],
            'password' => 'Aa16001600',
        ]);
        DB::table('model_has_roles')->where('model_id', $row[0])->delete();
        $user->syncRoles('Student');

        return $user;
    }
}
