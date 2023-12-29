<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    protected $fillable = [
        'name',
        'gender',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
