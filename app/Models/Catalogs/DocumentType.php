<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model
{
    use HasFactory;
    protected $table = 'document_types';
    protected $fillable = [
        'name',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
