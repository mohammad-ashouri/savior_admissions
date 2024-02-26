<?php

namespace App\Models\Finance;

use App\Models\Catalogs\AcademicYear;
use App\Models\StudentExtraInformation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;

    protected $table = 'tuitions';

    protected $fillable = [
        'academic_year',
    ];

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function allTuitions()
    {
        return $this->hasMany(TuitionDetail::class, 'tuition_id');
    }
}
