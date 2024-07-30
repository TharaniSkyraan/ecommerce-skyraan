<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['address','admin_ids','lat','lng','status'];

    
    public function productstock()
    {    
        return $this->hasMany(ProductStock::class, 'warehouse_id', 'id');
    }
}
