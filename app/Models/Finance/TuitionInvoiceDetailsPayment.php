<?php

namespace App\Models\Finance;

use App\Models\Catalogs\PaymentMethod;
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
        'payment_details',
        'payment_method',
        'amount',
        'status',
        'adder',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function tuitionInvoiceDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TuitionInvoiceDetails::class, 'invoice_details_id');
    }

    public function invoiceDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_id');
    }

    public function paymentMethodInfo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'id');
    }

    public function seconderInfo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'seconder', 'id');
    }

    public function adder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'adder', 'id');
    }
}
