<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','category_ids','slug','status'];
    
    protected $appends = ['is_default'];

    
    public function attribute_set()
    {
        return $this->hasOne(AttributeSet::class, 'attribute_id', 'id')->where('is_default','yes');
    }
    
    public function attributeSets()
    {
        return $this->hasMany(AttributeSet::class, 'attribute_id', 'id');
    }

    public function getIsDefaultAttribute()
    {
        $is_default = $this->attribute_set?$this->attribute_set->id:0;

        return $is_default;
    }

}

