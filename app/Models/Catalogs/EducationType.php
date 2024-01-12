<?php

namespace App\Models\Catalogs;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationType extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'education_types';
    protected $fillable = [
        'name',
        'description',
        'status',
        'adder',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function adder()
    {
        return $this->belongsTo(User::class, 'adder', 'id');
    }
}
