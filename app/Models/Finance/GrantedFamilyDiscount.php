<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrantedFamilyDiscount extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'granted_family_discounts';
    protected $fillable = [];
}
