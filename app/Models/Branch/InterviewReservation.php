<?php

namespace App\Models\Branch;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewReservation extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'interview_reservations';

    protected $fillable = [
        'interview_id',
        'student_id',
        'reservatore',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function interviewInfo()
    {
        return $this->belongsTo(Applications::class, 'interview_id', 'id');
    }
    public function studentInfo()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
    public function reservatoreInfo()
    {
        return $this->belongsTo(User::class, 'reservatore', 'id');
    }
}
