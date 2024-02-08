<?php

namespace App\Models\Branch;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewReservation extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'interviews';

    protected $fillable = [
        'interview_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function interviewInfo()
    {
        return $this->belongsTo(Interview::class, 'interview_id', 'id');
    }
    public function userInfo()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
