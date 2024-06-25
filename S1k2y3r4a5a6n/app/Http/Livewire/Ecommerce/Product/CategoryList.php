<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Category;

class CategoryList extends Component
{


    public function render()
    {
        $this->categories = Category::whereStatus('active')->where(function($q){
            $q->whereNotNull('parent_id')
             ->Orwhere(function($q1){
                $q1->whereHas('sub_categories', function ($query) {
                    $query->where('status', 'active');
                }, '=', 0);
             });
        })->get();

        return view('livewire.ecommerce.product.category-list');
    }
}
