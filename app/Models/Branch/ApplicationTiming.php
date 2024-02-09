<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationTiming extends Model
{
    use HasFactory;

    protected $table = 'application_timings';

    protected $fillable = [
        'academic_year',
        'students_application_type',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'interview_time',
        'delay_between_reserve',
        'interviewers',
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
}
