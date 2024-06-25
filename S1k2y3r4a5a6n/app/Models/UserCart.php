<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','postal_code','user_address_id','coupon_code','notes','applicable_products'];

    public function address()
    {
        return $this->belongsTo(SavedAddress::class, 'user_address_id', 'id');
    }
    
    public function coupon()
    {    
        return $this->belongsTo(Coupon::class, 'coupon_code', 'coupon_code');
    }
    
}
