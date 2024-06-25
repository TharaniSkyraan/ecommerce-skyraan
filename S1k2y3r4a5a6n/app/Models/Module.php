<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = ['module','parent_id','key','sort'];
    
    public function sub_modules()
    {
        return $this->hasMany(Module::class, 'parent_id', 'id')->orderBy('sort','asc');
    }
}
