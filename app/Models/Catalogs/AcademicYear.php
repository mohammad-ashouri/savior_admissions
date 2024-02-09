<?php

namespace App\Models\Catalogs;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'school_id',
        'start_date',
        'end_date',
        'levels',
        'employees',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function schoolInfo()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
}
