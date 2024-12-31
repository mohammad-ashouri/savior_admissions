<?php

namespace App\Models\Branch;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplianceConfirmationInformation extends Model
{
    use SoftDeletes;

    protected $table = 'appliance_confirmation_information';

    protected $fillable = [
        'id',
        'appliance_id',
        'date_of_referral',
        'date_of_confirm',
        'status',
        'description',
        'referrer',
        'seconder',
    ];

    public function referrerInfo()
    {
        return $this->belongsTo(User::class, 'referrer', 'id');
    }

    public function seconderInfo()
    {
        return $this->belongsTo(User::class, 'seconder', 'id');
    }
}
