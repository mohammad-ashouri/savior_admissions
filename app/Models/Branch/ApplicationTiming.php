<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationTiming extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'application_timings';

    protected $fillable = [
        'academic_year',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'interview_time',
        'delay_between_reserve',
        'first_interviewer',
        'second_interviewer',
        'fee',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function applications()
    {
        return $this->hasMany(Applications::class,'application_timing_id');
    }
    public function firstInterviewer()
    {
        return $this->belongsTo(User::class, 'first_interviewer', 'id');
    }
    public function secondInterviewer()
    {
        return $this->belongsTo(User::class, 'second_interviewer', 'id');
    }
}
