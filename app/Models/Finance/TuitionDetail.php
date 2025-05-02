<?php

namespace App\Models\Finance;

use App\Models\Catalogs\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuitionDetail extends Model
{
    protected $fillable = [
        'tuition_id',
        'level',
        'full_payment',
        'two_installment_payment',
        'four_installment_payment',
        'three_installment_payment',
        'seven_installment_payment',
        'full_payment_ministry',
        'three_installment_payment_ministry',
        'seven_installment_payment_ministry',
        'status',
    ];

    public function tuitionInfo(): BelongsTo
    {
        return $this->belongsTo(Tuition::class, 'tuition_id', 'id');
    }

    public function levelInfo(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level', 'id');
    }
}
