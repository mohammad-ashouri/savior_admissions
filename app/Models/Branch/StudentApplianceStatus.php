<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentApplianceStatus extends Model
{
    use HasFactory;
    protected $table = 'interviews';

    protected $fillable = [
        'student_id',
        'academic_year',
        'interview_status',
        'documents_uploaded',
        'documents_uploaded_approval',
        'documents_uploaded_seconder',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }
}
