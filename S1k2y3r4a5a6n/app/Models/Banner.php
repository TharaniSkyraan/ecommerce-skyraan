<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','product_ids','image','slug','status', 'description','product_type','promotion_banner','special_product'];

    protected $appends = ['product_slug','product_created'];
    

    public function getProductSlugAttribute()
    {
        if($this->product_type=='single'){
            $product_id = array_values(array_filter(explode(',',$this->product_ids)));
            $product_slug = Product::whereIn('id',$product_id)->pluck('slug')->first();
        }
        return $product_slug??'';
    }

    public function getProductCreatedAttribute()
    {
        if($this->product_type=='single'){
            $product_id = array_values(array_filter(explode(',',$this->product_ids)));
            $product_created = Product::whereIn('id',$product_id)->pluck('created_at')->first();
        }
        return $product_created??'';
    }

}


