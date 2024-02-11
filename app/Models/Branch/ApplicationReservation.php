<?php

namespace App\Models\Branch;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationReservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'application_reservations';

    protected $fillable = [
        'interview_id',
        'student_id',
        'reservatore',
        'level',
        'payment_status',
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

    public function studentInfo()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function reservatoreInfo()
    {
        return $this->belongsTo(User::class, 'reservatore', 'id');
    }
}
