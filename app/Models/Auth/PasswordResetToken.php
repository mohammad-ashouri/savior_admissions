<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordResetToken extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='password_reset_tokens';
    protected $connection='main';
    protected $fillable=[
        'user_id',
        'type',
        'token',
        'active'
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
}
