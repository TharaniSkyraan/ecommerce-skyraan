<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingAddress extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','phone','alternative_phone','state','city','country','address','landmark','zip_code','order_id','user_id'];

}
