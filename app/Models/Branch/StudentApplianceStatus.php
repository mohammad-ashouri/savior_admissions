<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\GrantedFamilyDiscount;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentApplianceStatus extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'student_appliance_statuses';

    protected $fillable = [
        'id',
        'student_id',
        'academic_year',
        'interview_status',
        'documents_uploaded',
        'documents_uploaded_approval',
        'documents_uploaded_seconder',
        'seconder_description',
        'date_of_document_approval',
        'tuition_payment_status',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function applianceInformation()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function studentInfo()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function studentInformations()
    {
        return $this->belongsTo(StudentInformation::class, 'student_id', 'student_id');
    }

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function documentSeconder()
    {
        return $this->belongsTo(User::class, 'documents_uploaded_seconder', 'id');
    }

    public function evidences()
    {
        return $this->belongsTo(Evidence::class, 'id', 'appliance_id');
    }

    public function tuitionInvoices()
    {
        return $this->belongsTo(TuitionInvoices::class, 'id', 'appliance_id');
    }

    public function levelInfo()
    {
        return $this->hasOne(ApplicationReservation::class, 'student_id', 'student_id')
            ->whereHas('applicationInfo', function ($query) {
                $query->where('reserved', 1);
            })
            ->latest('id');
    }

    public function grantedFamilyDiscounts()
    {
        return $this->belongsTo(GrantedFamilyDiscount::class, 'id', 'appliance_id');
    }
}
