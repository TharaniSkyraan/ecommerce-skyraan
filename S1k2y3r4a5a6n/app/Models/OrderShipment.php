<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderShipment extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['order_id','user_id','weight','cod_amount','cod_status','cross_checking_status','tracking_id',
    'shipping_company_name','tracking_link','estimate_date_shipped','date_shipped','note','status'];

    public function shipping_history()
    {
        return $this->hasMany(ShippingHistory::class, 'shipment_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shipping_status()
    {
        return $this->belongsTo(ShippingStatus::class, 'status', 'slug');
    }

}
