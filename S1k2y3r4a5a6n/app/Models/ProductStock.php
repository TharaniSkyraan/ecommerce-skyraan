<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
 
    protected $fillable = ['id','product_name','warehouse_id','product_id','product_variant_id','available_quantity','stock_status','created_at','updated_at'];

    public function warehouse()
    {    
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
       
    public function product()
    {    
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
