<?php

namespace App\Models\Finance;

use App\Models\Branch\StudentApplianceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoices extends Model
{
    use SoftDeletes;

    protected $table = 'tuition_invoices';

    protected $fillable = [
        'appliance_id',
        'payment_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function applianceInformation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StudentApplianceStatus::class, 'appliance_id','id');
    }

    public function invoiceDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TuitionInvoiceDetails::class, 'tuition_invoice_id');
    }

    public function getJalaliCreatedAtAttribute()
    {
        return \Morilog\Jalali\Jalalian::fromDateTime($this->created_at)->format('H:i');
    }
}
