<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralInformation extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'general_informations';
    protected $fillable = [
        'user_id',
        'father_name',
        'gender',
        'birthdate',
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
        return $this->belongsTo(User::class,'user_id','id');
    }
}
