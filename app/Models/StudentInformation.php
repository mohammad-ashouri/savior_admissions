<?php

namespace App\Models;

use App\Models\Catalogs\StudentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInformation extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'student_informations';
    protected $fillable = [
        'student_id',
        'parent_father_id',
        'parent_mother_id',
        'guardian',
        'releationship',
        'current_nationality',
        'current_identification_type',
        'current_identification',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
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

    public function nationalityInfo()
    {
        return $this->belongsTo(Country::class, 'current_nationality', 'id');
    }

    public function statusInfo()
    {
        return $this->belongsTo(StudentStatus::class, 'status', 'id');
    }


}
