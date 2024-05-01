<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoiceDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tuition_invoice_details';

    protected $fillable = [
        'tuition_invoice_id',
        'invoice_id',
        'amount',
        'is_paid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
