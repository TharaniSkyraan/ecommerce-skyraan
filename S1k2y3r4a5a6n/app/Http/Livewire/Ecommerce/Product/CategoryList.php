<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Category;

class CategoryList extends Component
{


    public function render()
    {
        $this->categories1 = Category::whereStatus('active')
                                    ->whereNull('parent_id')
                                    ->orderBy('sort','asc')
                                    ->limit(7)->get();
        $this->categories = Category::whereStatus('active')
                                    ->whereNull('parent_id')
                                    ->orderBy('sort','asc')
                                    ->skip(7)->take(PHP_INT_MAX)->get();
        $category_ids = Category::whereStatus('active')
                                    ->whereNull('parent_id')
                                    ->orderBy('sort','asc')
                                    ->skip(7)->take(PHP_INT_MAX)
                                    ->pluck('id')->toArray();
        $count = Category::where(function($query) use ($category_ids) {
                                $query->whereIn('id', $category_ids)
                                      ->orWhereIn('parent_id', $category_ids);
                            })->whereStatus('active')->count();
        $class_name = 3;
        $divide = 4;
        if($count<10){
            $class_name = 12;
            $divide = 1;
        }elseif($count<20){
            $class_name = 6;
            $divide = 2;
        }elseif($count<30){
            $class_name = 4;
            $divide = 3;
        }

        $column = round($count/12, 0);
        
        $this->morecategoriescount = ($count!=0)?(round($count/$divide, 0)+1):0;
        $this->more_class_name = $class_name;

        return view('livewire.ecommerce.product.category-list');
    }
}
