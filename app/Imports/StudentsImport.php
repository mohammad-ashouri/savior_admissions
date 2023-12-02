<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;
use App\Models\GeneralInformation;

class StudentsImport implements ToModel
{
    public function model(array $row)
    {
        // Define how each row in the Excel file should be mapped to your database model
        return new User([
            'id' => $row[0],
            'name' => $row[1],
            'family' => $row[2],
            'mobile' => $row[9],
            'email' => $row[12],
            'password' => 'Aa16001600',
        ]);
    }
}
