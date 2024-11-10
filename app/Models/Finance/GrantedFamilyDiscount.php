<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrantedFamilyDiscount extends Model
{
    use SoftDeletes;
    protected $table = 'granted_family_discounts';
    protected $fillable = [
        'id',
        'appliance_id',
        'level',
        'discount_percentage',
        'discount_price',
        'signed_child_number',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
