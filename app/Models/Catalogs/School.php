<?php

namespace App\Models\Catalogs;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    protected $fillable = [
        'name',
        'persian_name',
        'gender',
        'educational_charter',
        'status',
        'address',
        'address_fa',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function genderInfo()
    {
        return $this->belongsTo(Gender::class, 'gender', 'id');
    }
}
