<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = [
        'name',
        'status',
    ];
    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];
}
