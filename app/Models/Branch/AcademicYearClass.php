<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\EducationType;
use App\Models\Catalogs\Level;
use App\Models\Catalogs\School;
use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYearClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'academic_year_classes';

    protected $fillable = [
        'name',
        'academic_year',
        'level',
        'education_type',
        'capacity',
        'education_gender',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'school_id', 'id');
    }

    public function levelInfo()
    {
        return $this->belongsTo(Level::class, 'level', 'id');
    }

    public function educationTypeInfo()
    {
        return $this->belongsTo(EducationType::class, 'education_type', 'id');
    }

    public function educationGenderInfo()
    {
        return $this->belongsTo(Gender::class, 'education_gender', 'id');
    }
}
