<?php

namespace App\Models\Branch;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dropout extends Model
{
    protected $table = 'dropouts';

    protected $fillable = [
        'appliance_id',
        'description',
        'files',
        'user',
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

    public function applianceInfo(): BelongsTo
    {
        return $this->belongsTo(StudentApplianceStatus::class, 'appliance_id');
    }

    public function userInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }
}
