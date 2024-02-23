<?php

namespace App\Models\Finance;

use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;

    protected $table = 'tuition';

    protected $fillable = [
        'academic_year',
        'level',
        'price',
        'status',
    ];

    public function academicYearInfo()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
    }

    public function levelInfo()
    {
        return $this->belongsTo(Level::class, 'level', 'id');
    }
}
