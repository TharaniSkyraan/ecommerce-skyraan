<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['coupon_code','count','unlimited_coupon','display_at_checkout','discount','minimum_order','discount_type', 'apply_for','apply_for_ids','status','start_date','end_date','never_expired'];

    protected $append=['expired_status'];

    public function isExpired()
    {
        return now()->greaterThanOrEqualTo($this->end_date);
    }

    public function getExpiredStatusAttribute()
    {
        return $this->isExpired();
    }

    public function orders()
    {    
        return $this->hasMany(Order::class, 'coupon_code', 'coupon_code')->withTrashed();
    }

    public function user_orders()
    {   
        return $this->hasMany(Order::class, 'coupon_code', 'coupon_code')->where('user_id','=',auth()->user()->id);
    }

    public function getApplyForProductAttribute()
    {
        $product_ids = '';
        if($this->apply_for=='product'){
            $product_ids = $this->apply_for_ids;
        }else
        if($this->apply_for=='category'){
            $category_ids = ',('.implode('|',explode(',',$this->apply_for_ids)).'),';
            $product_ids = ','.implode(',',Product::where('category_ids', 'REGEXP', $category_ids)->pluck('id')->toArray()).',';
        }else
        if($this->apply_for=='collection'){
            $product_ids = Collection::where('id',$this->apply_for_ids)->pluck('product_ids')->first();
        }

        return $product_ids;
    }

}
