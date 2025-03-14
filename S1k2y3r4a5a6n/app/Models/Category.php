<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','parent_id','image','logo','slug','status', 'description'];
    

    public function parent_category()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')->where('status','active');
    }
    public function sub_categories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->orderBy('sort','asc');
    }
    public function active_sub_categories()
    {
        return $this->sub_categories->where('status','active');
    }

}
