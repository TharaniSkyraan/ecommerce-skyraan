<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSearches extends Model
{
    protected $table = 'product_searches';

    use HasFactory;

    protected $fillable = ['category_ids','warehouse_ids','status'];
    
    public function productvariant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }   
    public function getOrderItemAttribute(){
        
        $attribute_set_ids = explode(',',$this->attribute_set_ids);

        $order = OrderItem::whereProductId($this->product_id);
        foreach($attribute_set_ids as $set_id){
            $order->whereRaw("FIND_IN_SET(?, attribute_set_ids)", [$set_id]);
        }
        $order = $order->first();

        return $order;
    }
}
