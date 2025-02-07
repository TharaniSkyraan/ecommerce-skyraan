<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyingOption extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','image','logo','slug','status', 'description','feature_type'];
}
