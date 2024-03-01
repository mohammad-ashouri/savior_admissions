<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentApplianceStatus extends Model
{
    use HasFactory;

    protected $table = 'student_appliance_statuses';

    protected $fillable = [
        'student_id',
        'academic_year',
        'interview_status',
        'documents_uploaded',
        'documents_uploaded_approval',
        'documents_uploaded_seconder',
        'tuition_payment_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function studentInfo()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }
}