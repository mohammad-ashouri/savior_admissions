<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'src',
        'adder',
    ];
    protected $hidden = [
        'adder',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
