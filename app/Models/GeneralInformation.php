<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralInformation extends Model
{
    use HasFactory,softDeletes;
    protected $fillable = [
        'father_name',
        'birthdate',
        'country',
        'state/city',
        'address',
        'phone',
        'postal_code',
    ];
    protected $hidden = [
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
