<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['sku','product_id','product_name','created_by','price','sale_price','cost_per_item','search_price',
                           'available_quantity','shipping_wide','shipping_length','shipping_height',
                           'shipping_weight','discount_expired','discount_start_date','discount_end_date',
                           'discount_duration','images','stock_status','is_default','cart_limit'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function product_attribute_set()
    {
        return $this->hasMany(ProductAttributeSet::class, 'product_variant_id', 'id');
    }

    public function getProductImageAttribute()
    {
        if (empty($this->images) || $this->images == '[]') {
            $productImages = json_decode($this->product->images, true);
            return !empty($productImages) ? $productImages[0] : null;
        }
    
        $images = json_decode($this->images, true);
        return !empty($images) ? $images[0] : null;
    }
    public function getSetAttribute()
    {
        $attributeSetIds = $this->product_attribute_set->pluck('attribute_set_id')->toArray();
        $attributeSets = AttributeSet::whereIn('id', $attributeSetIds)->pluck('name')->toArray();

        return (implode('| ', $attributeSets));
    }

    public function product_stock()
    {
        return $this->hasMany(ProductStock::class, 'product_variant_id', 'id');
    }    

    
}
