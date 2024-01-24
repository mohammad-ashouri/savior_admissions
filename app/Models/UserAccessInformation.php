<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessInformation extends Model
{
    use HasFactory;

    protected $table = 'user_access_informations';
    protected $fillable = [
        'user_id',
        'principal',
        'admissions_officer',
        'financial_manager',
        'interviewer',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function userInfo()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
