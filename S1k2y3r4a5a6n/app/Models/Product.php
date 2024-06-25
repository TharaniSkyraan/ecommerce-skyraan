<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable =['name','slug','images','label_id','category_ids','tax_ids','brand','status','description','content','related_product_ids','cross_selling_product_ids','attribute_ids','rating'];
   
    public function product_variant()
    {
        return $this->hasOne(ProductVariant::class, 'product_id', 'id')->where('is_default','yes');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_ids', 'id')->where('status','active');
    }

}
