<?php

namespace App\Models\Finance;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoiceDetailsPayment extends Model
{
    use SoftDeletes;

    protected $table = 'tuition_invoice_details_payments';

    protected $fillable = [
        'invoice_details_id',
        'invoice_id',
        'amount',
        'adder',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function tuitionInvoiceDetails()
    {
        return $this->hasMany(TuitionInvoiceDetails::class, 'invoice_details_id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(Invoice::class, 'invoice_id');
    }

    public function adder()
    {
        return $this->hasMany(User::class, 'adder');
    }
}
