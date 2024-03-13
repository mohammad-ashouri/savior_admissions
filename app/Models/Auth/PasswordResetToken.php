<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;
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
