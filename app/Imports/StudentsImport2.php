<?php
namespace App\Imports;

use App\Models\GeneralInformation;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport2 implements ToModel
{
    public function model(array $row)
    {
        // Define how each row in the Excel file should be mapped to your database model
        return new GeneralInformation([
            'user_id' => $row[0],
            'first_name' => $row[1],
            'last_name' => $row[2],
            'gender' => $row[3],
            'birthdate' => $row[5],
            'birthplace' => $row[6],
            'nationality' => $row[7],
            'passport_number' => $row[4],
            'faragir_code' => $row[8],
            'address' => $row[11],
            'phone' => $row[10],
        ]);
    }
}
