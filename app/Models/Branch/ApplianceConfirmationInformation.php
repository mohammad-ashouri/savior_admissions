<?php

namespace App\Models\Branch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplianceConfirmationInformation extends Model
{
    use SoftDeletes;

    protected $table = 'appliance_confirmation_information';

    protected $fillable = [
        'id',
        'appliance_id',
        'status',
        'description',
        'adder'
    ];
}
