<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeSet extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['product_id','product_variant_id','attribute_id','attribute_set_slug','attribute_set_id'];

    public function attribute_set()
    {
        return $this->belongsTo(AttributeSet::class, 'attribute_set_id', 'id');
    }
}
