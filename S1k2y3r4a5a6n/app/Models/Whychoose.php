<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whychoose extends Model
{
    use HasFactory;
    protected $fillable = ['why_chs_title','why_chs_desc','why_chs_img'];
    
}
