<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionInvoiceEditHistoryFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tuition_invoice_edit_history_files';

    protected $fillable = [
        'history_id',
        'file_name',
        'file_path',
        'file_type',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function history(): BelongsTo
    {
        return $this->belongsTo(TuitionInvoiceEditHistory::class, 'history_id');
    }
} 