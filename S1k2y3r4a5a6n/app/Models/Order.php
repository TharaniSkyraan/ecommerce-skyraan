<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
   
    protected $fillable = ['code','user_id','invoice_number','invoice_date','coupon_code','sub_total','total_amount','shipping_amount','discount_amount','description','status','is_confirmed','is_finished','payment_id','completed_at'];
   
    protected $append = ['order_histories'];

    public function payments()
    {
        return $this->belongsTo(OrderPayment::class, 'payment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shipmentAddress()
    {
        return $this->hasOne(ShippingAddress::class, 'order_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function shipment()
    {
        return $this->hasOne(OrderShipment::class, 'order_id', 'id');
    }

    public function order_history()
    {
        return $this->hasMany(OrderHistory::class, 'order_id', 'id');
    }

    public function getOrderHistoriesAttribute()
    {
        $order_confirmed = $this->order_history->where('action','order_confirmed')->pluck('created_at')->first();
        $shipped = $this->order_history->where('action','shipped')->pluck('created_at')->first();
        $out_for_delivery = $this->order_history->where('action','out_for_delivery')->pluck('created_at')->first();
        $delivered = $this->order_history->where('action','delivered')->pluck('created_at')->first();
        $cancelled = $this->order_history->where('action','cancelled')->pluck('created_at')->first();
        $refund = $this->order_history->where('action','refund')->pluck('created_at')->first();
        $replaced = $this->order_history->where('action','replaced')->pluck('created_at')->first();
      
        $statuses['order_placed'] = $this->created_at->copy()->timezone('Asia/Kolkata');
        $statuses['order_confirmed'] = ($order_confirmed)?$order_confirmed->copy()->timezone('Asia/Kolkata'):$order_confirmed;
        $statuses['shipped'] = ($shipped)?$shipped->copy()->timezone('Asia/Kolkata'):$shipped;
        $statuses['out_for_delivery'] = ($out_for_delivery)?$out_for_delivery->copy()->timezone('Asia/Kolkata'):$out_for_delivery;
        $statuses['delivered'] = ($delivered)?$delivered->copy()->timezone('Asia/Kolkata'):$delivered;
        $statuses['cancelled'] = ($cancelled)?$cancelled->copy()->timezone('Asia/Kolkata'):$cancelled;
        $statuses['refund'] = ($refund)?$refund->copy()->timezone('Asia/Kolkata'):$refund;
        $statuses['replaced'] = ($replaced)?$replaced->copy()->timezone('Asia/Kolkata'):$replaced;
        
        return $statuses;
    }
}
