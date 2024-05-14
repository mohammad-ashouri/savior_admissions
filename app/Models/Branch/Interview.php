<?php

namespace App\Models\Branch;

use App\Models\Catalogs\AcademicYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'interviews';

    protected $fillable = [
        'application_id',
        'interview_form',
        'files',
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

    public function applicationInfo()
    {
        return $this->belongsTo(Applications::class, 'application_id', 'id');
    }
}
