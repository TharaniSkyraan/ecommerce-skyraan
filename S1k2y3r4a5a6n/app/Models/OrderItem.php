<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $append=['order_code','variant'];
    
    protected $fillable = ["order_id","product_id","product_name","product_image","quantity","weight","wide","height","length",
                           "price", "sale_price", "tax", "tax_id", "tax_amount", "taxable_amount", "gross_amount","discount_amount","sub_total",
                           "shipping_charge", "shipping_tax", "shipping_tax_id", "shipping_tax_amount", "shipping_taxable_amount", "shipping_gross_amount", "shipping_discount_amount", "shipping_sub_total",
                           "total_amount", "attribute_set_ids"];
    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }    
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
    public function review()
    {
        $user_id = auth()->user()->id??'';
        return $this->hasOne(Review::class, 'product_id', 'product_id')->where('user_id','=',$user_id);
    }

    public function getOrderCodeAttribute()
    {
        return $this->orders->code??'';
    }

    public function getVariantAttribute()
    {
        $zone = \Session::get('zone_config');
        $warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));

        $attribute_ids = array_filter(explode(',',$this->attribute_set_ids));
        $default = ProductVariant::select('id','price','images','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration')
                    ->where(function($q) use($attribute_ids) {
                        foreach($attribute_ids as $set_id){
                            $q->whereHas('product_attribute_set', function($q1) use($set_id){
                                $q1->whereAttributeSetId($set_id);
                            });
                        }
                    })->whereProductId($this->product_id)->first();
                    
        $product_stock = ProductStock::select('id', 'available_quantity')
                                    ->whereIn('warehouse_id',$warehouse_ids)
                                    ->whereProductVariantId($default->id??'')
                                    ->groupBy('id', 'available_quantity')
                                    ->orderBy('available_quantity','desc')
                                    ->first();

        $default->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';

        return $default;

    }

}
