<?php

namespace App\Models;

use App\Models\Catalogs\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'documents';
    protected $fillable = [
        'user_id',
        'document_type_id',
        'src',
        'description',
        'remover',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class,'document_type_id','id');
    }
    public function documentOwner()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
