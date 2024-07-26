<?php

namespace App\ExcelExports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersWithMobile implements FromCollection
{
    public function collection()
    {
        return User::join('general_informations', 'users.id', '=', 'general_informations.user_id')
            ->whereNotNull('users.mobile')
            ->whereHas('roles',function ($query){
                $query->whereName('Parent');
            })
            ->select('users.mobile','general_informations.first_name_en','general_informations.last_name_en')
            ->orderBy('general_informations.last_name_en')
            ->orderBy('general_informations.first_name_en')
            ->get();
    }
}
