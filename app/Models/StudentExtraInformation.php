<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExtraInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_extra_informations';
    protected $fillable = [
        'student_informations_id',
        'name',
        'description',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function studentInformation()
    {
        return $this->belongsTo(StudentInformation::class, 'student_informations_id', 'id');
    }
}
