<?php

namespace App\Models;

use App\Models\Catalogs\CurrentIdentificationType;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Catalogs\StudentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInformation extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'student_informations';

    protected $fillable = [
        'student_id',
        'parent_father_id',
        'parent_mother_id',
        'guardian',
        'guardian_student_relationship',
        'current_nationality',
        'current_identification_type',
        'current_identification_code',
        'sida_code',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function studentInfo()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function fatherInfo()
    {
        return $this->belongsTo(User::class, 'parent_father_id', 'id');
    }

    public function motherInfo()
    {
        return $this->belongsTo(User::class, 'parent_mother_id', 'id');
    }

    public function guardianInfo()
    {
        return $this->belongsTo(User::class, 'guardian', 'id');
    }

    public function guardianRelationshipInfo()
    {
        return $this->belongsTo(GuardianStudentRelationship::class, 'guardian_student_relationship', 'id');
    }

    public function nationalityInfo()
    {
        return $this->belongsTo(Country::class, 'current_nationality', 'id');
    }

    public function identificationTypeInfo()
    {
        return $this->belongsTo(CurrentIdentificationType::class, 'current_identification_type', 'id');
    }

    public function statusInfo()
    {
        return $this->belongsTo(StudentStatus::class, 'status', 'id');
    }

    public function extraInformations()
    {
        return $this->hasMany(StudentExtraInformation::class, 'student_informations_id');
    }

    public function generalInformations()
    {
        return $this->belongsTo(GeneralInformation::class, 'student_id', 'user_id');
    }

    public function userInfo()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
}
