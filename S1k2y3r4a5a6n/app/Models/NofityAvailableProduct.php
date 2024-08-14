<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NofityAvailableProduct extends Model
{
    use HasFactory;

    protected $fillable = ['email','product_id','product_variant_id','status','attempts'];
   
    protected $appends = ['address'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function getAddressAttribute()
    {
        return $this->user->address??null;
    }
}

