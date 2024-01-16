<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentsParent extends Model
{
    use HasFactory, softDeletes;

    protected $table = 'general_informations';
    protected $fillable = [
        'student_id',
        'parent_father_id',
        'parent_mother_id',
        'editor',
    ];
    protected $hidden = [
        'editor',
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

    public function editorInfo()
    {
        return $this->belongsTo(User::class, 'editor', 'id');
    }
}
