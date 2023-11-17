<?php

namespace App\Models\UsersModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleList extends Model
{
    use HasFactory;
    protected $fillable = ['title'];
    protected $hidden = ['created_at', 'updated_at'];
}
