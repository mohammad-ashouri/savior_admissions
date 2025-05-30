<?php

namespace App\Models\Finance;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoiceEditHistory extends Model
{
    use SoftDeletes;

    protected $table = 'tuition_invoice_edit_histories';

    protected $fillable = [
        'invoice_details_id',
        'description',
        'file',
        'user',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function tuitionInvoiceDetails(): HasMany
    {
        return $this->hasMany(TuitionInvoiceDetails::class, 'invoice_details_id');
    }

    public function userInfo(): belongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

}
