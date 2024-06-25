<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['order_id','user_id','currency','charge_id','payment_chennal','amount','status','payment_type','refunded_amount','refund_note'];
   
   
}
