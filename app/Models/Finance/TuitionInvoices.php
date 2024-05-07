<?php

namespace App\Models\Finance;

use App\Models\Branch\StudentApplianceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoices extends Model
{
    use HasFactory,SoftDeletes;

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

    public function applianceInformation()
    {
        return $this->belongsTo(StudentApplianceStatus::class, 'appliance_id','id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(TuitionInvoiceDetails::class, 'tuition_invoice_id');
    }
}
