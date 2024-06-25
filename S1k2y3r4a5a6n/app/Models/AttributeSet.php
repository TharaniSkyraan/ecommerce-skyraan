<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeSet extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['attribute_id','name','slug','image','color','is_default'];
    
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
    
}
