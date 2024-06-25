<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingHistory extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['order_id','user_id','action','shipment_id','description'];
    

    public function shipping_status()
    {
        return $this->belongsTo(ShippingStatus::class, 'action', 'slug');
    }

}
