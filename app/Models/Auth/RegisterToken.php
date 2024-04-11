<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterToken extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'register_tokens';

    protected $fillable = [
        'register_method',
        'value',
        'token',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
