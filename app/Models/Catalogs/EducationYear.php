<?php

namespace App\Models\Catalogs;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationYear extends Model
{
    use HasFactory;

    protected $table = 'education_years';
    protected $fillable = [
        'start',
        'finish',
        'active',
        'starter',
        'finisher',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function starterInfo()
    {
        return $this->belongsTo(User::class, 'starter', 'id');
    }

    public function finisherInfo()
    {
        return $this->belongsTo(User::class, 'finisher', 'id');
    }

}
