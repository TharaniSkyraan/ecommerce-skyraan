<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockUpdateQuantityHistory extends Model
{
    use HasFactory;

    protected $fillable = [ 'history_id','warehouse_id','product_name','product_id','product_variant_id','previous_available_quantity','updated_quantity','available_quantity'];
       
    public function warehouse()
    {    
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function history()
    {    
        return $this->belongsTo(StockHistory::class, 'history_id', 'id');
    }
}
