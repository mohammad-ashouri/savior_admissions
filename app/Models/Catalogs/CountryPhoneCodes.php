<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryPhoneCodes extends Model
{
    use HasFactory;

    protected $table = 'country_phone_codes';

    protected $fillable = [
        'name',
        'phonecode',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
