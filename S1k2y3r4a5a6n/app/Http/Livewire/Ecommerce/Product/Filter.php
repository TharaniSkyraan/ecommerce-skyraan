<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class Filter extends Component
{

   
    public $stocks = ['in_stock'=>'In stock', 'out_of_stock'=>'Out of stock']; // Your options array

    public $ratings = [1,2,3,4,5]; 

    public $min = 0;
    
    public $max, $max_price, $type;

    public $min_price = 0;

    public $selectedStocks = [];

    public $rating_ids = [];

    public $category_ids = [];

    public $filters = [];

    public $filtercount = 0;

    protected $listeners = ['PopFilters'];
    
    public function filterCount()
    {
        
        $filters = [];
        $i=0;
        
        $availablestock = array_keys(array_filter(array_filter($this->selectedStocks, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        $rating = array_keys(array_filter(array_filter($this->rating_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));

        $category = array_keys(array_filter(array_filter($this->category_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        if(count($availablestock)!=0){
            $filters['availablestock'] = $availablestock;
            $i += 1;
        }else{
            $this->selectedStocks = [];
        }
        if(count($rating)!=0){
            $filters['rating'] = $rating;
            $i += 1;
        }else{
            $this->rating_ids = [];
        }
        if(count($category)!=0){
            $filters['category'] = $category;
            $i += 1;
        }else{
            $this->category_ids = [];
        }
        if(($this->max != $this->max_price)||($this->min != $this->min_price)){
            $filters['min_price'] = $this->min_price;
            $filters['max_price'] = $this->max_price;
            $i += 1;
        }
        $this->filtercount = $i;
        
        $this->emit('GetFilters', $filters);
        unset($filters['category']);
        $this->filters = $filters;

    }
    
    public function PopFilters($filters)
    {   
        foreach($filters as $key =>$filter){
            if($key=='category'){
                $key = 'category_ids';
            }if($key=='availablestock'){
                $key = 'selectedStocks';
            }if($key=='rating'){
                $key = 'rating_ids';
            }
            
            $this->$key = (is_array($filter))?array_fill_keys($filter, true):$filter;
        }
        $this->filterCount();
        $this->emit('disbaleLoader');
    }

    public function updatedselectedStocks()
    {

        $this->filterCount();
    }

    public function updatedCategoryIds()
    {
        $this->filterCount();
    }

    public function updatedRatingIds()
    {
        $this->filterCount();
    }

    public function updatedMaxPrice()
    {
        $this->filterCount();
    }

    public function updatedMinPrice()
    {
        $this->filterCount();
    }

    public function resetAvailable()
    {
        $this->reset(['selectedStocks']); 
        $this->filterCount();
    }

    public function resetCategory()
    {
        $this->reset(['category_ids']);  
        $this->filterCount();
    }

    public function resetRating()
    {
        $this->reset(['rating_ids']); 
        $this->filterCount(); 
    }

    public function resetPrice()
    {        
        $this->max_price = $this->max;
        $this->min_price = $this->min;
        $this->filterCount();
    }

    public function mount($type)
    {
        $this->type = $type;
        $price = ProductVariant::orderBy('price','desc')->pluck('price')->first();
        $this->max_price = $this->max = round($price,0);
        
        $this->categories = Category::whereStatus('active')->where(function($q){
            $q->whereNotNull('parent_id')
             ->Orwhere(function($q1){
                $q1->whereHas('sub_categories', function ($query) {
                    $query->where('status', 'active');
                }, '=', 0);
             });
        })->get();

        
    }
    
    public function ResetAllFilters(){
        $this->max_price = $this->max;
        $this->min_price = $this->min;
        $this->reset(['category_ids','rating_ids','selectedStocks']);  
        $this->filterCount();
    }
    
    public function render()
    {

        $category_ids = array_keys(array_filter(array_filter($this->category_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));

        $stocks = array_keys(array_filter(array_filter($this->selectedStocks, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));

        $rating = array_keys(array_filter(array_filter($this->rating_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        

        return view('livewire.ecommerce.product.filter');
    }

}
