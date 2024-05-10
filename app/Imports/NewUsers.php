<?php

namespace App\Imports;

use App\Models\GeneralInformation;
use App\Models\StudentInformation;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class NewUsers implements ToModel
{
    public function model(array $row)
    {

        if ($row[2] != null) {
            $studentID = $row[2];

            $studentInformation = User::where('id',$studentID)->first();
            if (empty($studentInformation)) {
                $studentInformation=User::create([
                    'id'=>$studentID,
                    'password'=>bcrypt('Aa16001600'),
                ]);
                $studentInformation->assignRole('Student');
            }

            switch ($row[16]) {
                case 'FATHER':
                    $mobile = $row[23];
                    $email = $row[24];

                    $firstName=$row[18];
                    $lastName=$row[19];
                    $passport=$row[20];
                    $nationality=$row[21];
                    break;
                case 'MOTHER':
                    $mobile = $row[31];
                    $email = $row[32];

                    $firstName=$row[26];
                    $lastName=$row[27];
                    $passport=$row[28];
                    $nationality=$row[29];
                    break;
            }
            $guardianUser = User::where('mobile', $mobile)->first();
            if (empty($guardianUser)){
                $guardianUser=User::create([
                    'mobile'=>$mobile,
                    'email'=>$email,
                    'password'=>bcrypt(substr($mobile,-4)),
                ]);
                $guardianUser->assignRole('Parent');

                GeneralInformation::create([
                    'user_id'=>$guardianUser->id,
                    'first_name_en'=>$firstName,
                    'last_name_en'=>$lastName,
                    'passport_number'=>$passport,
                    'nationality'=>$nationality,
                ]);
            }

            switch ($row[16]) {
                case 'FATHER':
                    $mobile = $row[23];
                    $email = $row[24];
                    break;
                case 'MOTHER':
                    $mobile = $row[31];
                    $email = $row[32];
                    break;
            }

            $checkGeneralInformation=GeneralInformation::where('user_id',$row[2])->updateOrCreate([
                'user_id' => $studentInformation->id,
                'first_name_fa' => $row[5],
                'last_name_fa' => $row[6],
                'first_name_en' => $row[3],
                'last_name_en' => $row[4],
                'gender' => $row[8],
                'birthdate' => $row[9],
                'birthplace' => $row[10],
                'passport_number' => $row[11],
                'nationality' => $row[12],
                'iranian_residence_code' => $row[13],
                'faragir_code' => $row[15],
                'address' => $row[33],
            ]);



            return new StudentInformation([
                'student_id' => $studentInformation->id,
                'guardian' => $guardianUser->id,
                'sida_code' => $row[14],
            ]);
        }
    }
}
