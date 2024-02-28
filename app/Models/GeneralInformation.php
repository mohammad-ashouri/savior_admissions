<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralInformation extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'general_informations';

    protected $fillable = [
        'user_id',
        'first_name_en',
        'last_name_en',
        'first_name_fa',
        'last_name_fa',
        'father_name',
        'gender',
        'birthdate',
        'birthplace',
        'nationality',
        'passport_number',
        'faragir_code',
        'country',
        'state_city',
        'address',
        'phone',
        'postal_code',
        'adder',
        'editor',
    ];

    protected $hidden = [
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function birthplaceInfo()
    {
        return $this->belongsTo(Country::class, 'birthplace', 'id');
    }

    public function nationalityInfo()
    {
        return $this->belongsTo(Country::class, 'nationality', 'id');
    }

    public function countryInfo()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}
