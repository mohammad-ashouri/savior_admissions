<?php

namespace App\Models\Finance;

use App\Models\Catalogs\PaymentMethod;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoiceDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tuition_invoice_details';

    protected $fillable = [
        'tuition_invoice_id',
        'payment_method',
        'invoice_id',
        'amount',
        'is_paid',
        'description',
        'payment_details',
        'date_of_payment',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function tuitionInvoiceDetails()
    {
        return $this->belongsTo(TuitionInvoices::class, 'tuition_invoice_id','id');
    }

    public function invoiceDetails()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id','id');
    }

    public function paymentMethodInfo()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method','id');
    }
}
