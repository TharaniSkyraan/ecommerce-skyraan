<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['address','name','admin_ids','lat','lng','status','previous_zone_ids'];

    public function productstock()
    {    
        return $this->hasMany(ProductStock::class, 'warehouse_id', 'id');
    }

}
