<?php

namespace App\Models\Branch;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applications extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'applications';

    protected $fillable = [
        'application_timing_id',
        'date',
        'start_from',
        'ends_to',
        'first_interviewer',
        'second_interviewer',
        'reserved',
        'Interviewed',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function applicationTimingInfo()
    {
        return $this->belongsTo(ApplicationTiming::class, 'application_timing_id', 'id');
    }

    public function firstInterviewerInfo()
    {
        return $this->belongsTo(User::class, 'first_interviewer', 'id');
    }
    public function secondInterviewerInfo()
    {
        return $this->belongsTo(User::class, 'second_interviewer', 'id');
    }
    public function reservationInfo()
    {
        return $this->hasOne(ApplicationReservation::class, 'application_id', 'id')->where('payment_status',1);
    }

    public function interview()
    {
        return $this->hasMany(Interview::class, 'application_id')->orderBy('interview_type');
    }

}
