<?php

namespace App\Models\Finance;

use App\Models\Catalogs\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'tuition_id',
        'level',
        'price',
        'status',
    ];

    public function tuitionInfo()
    {
        return $this->belongsTo(Tuition::class, 'tuition_id', 'id');
    }

    public function levelInfo()
    {
        return $this->belongsTo(Level::class, 'level', 'id');
    }
}
