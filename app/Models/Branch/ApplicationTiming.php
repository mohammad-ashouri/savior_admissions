<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationTiming extends Model
{
    use SoftDeletes;

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
        'grades',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'grades' => 'array'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function academicYearInfo(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Applications::class,'application_timing_id');
    }
    public function firstInterviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_interviewer', 'id');
    }
    public function secondInterviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_interviewer', 'id');
    }

    public function gradeModels()
    {
        return Level::whereIn('id', $this->grades ?? [])->get();
    }
}
