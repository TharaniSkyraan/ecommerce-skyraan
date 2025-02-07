<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;
    
    protected $fillable = ['stock_type', 'warehouse_to_id', 'warehouse_from_id', 'reference_number', 'sent_date', 'received_date', 'status'];
    
    public function warehouse_from()
    {    
        return $this->belongsTo(Warehouse::class, 'warehouse_from_id', 'id');
    }
    
    public function warehouse_to()
    {    
        return $this->belongsTo(Warehouse::class, 'warehouse_to_id', 'id');
    }
    
    public function order()
    {    
        return $this->belongsTo(Order::class, 'reference_number', 'code');
    }
    
    public function modify()
    {    
        return $this->hasOne(StockHistory::class, 'reference_number', 'reference_number')->where('stock_type','=','modify');
    }
    
    public function updatedproducts()
    {    
        return $this->hasMany(ProductStockUpdateQuantityHistory::class, 'history_id', 'id');
    }

}
