<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSearches extends Model
{
    protected $table = 'product_searches';

    use HasFactory;

    protected $fillable = ['category_ids','warehouse_ids','status'];
    
}
