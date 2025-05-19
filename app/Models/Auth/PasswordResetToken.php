<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordResetToken extends Model
{
    use SoftDeletes;
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
