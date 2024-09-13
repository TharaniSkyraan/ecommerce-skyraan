<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Tax;
use App\Models\ProductSearches;
use App\Models\Review;
use Carbon\Carbon;
class ProductSearch extends Component
{
    public $show_result = false;
    public $query;
    public $products = [];
    public $warehouse_ids = [];
    protected $listeners = ['productsuggestion', 'unsetproductsuggestion'];

    public function mount(){
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
        $this->productList();
    }
    public function updatedQuery(){
       
        $this->productList();
    }

    public function Search(){
        if(!empty($this->query))
        return redirect(route('ecommerce.product.list',['search']).'?q='.$this->query);
    }

    public function productList(){
        
        $warehouse_ids = (count($this->warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $this->warehouse_ids)) . ')(,|$)':'';

        $Products = ProductSearches::where('status','active')
                                    ->where(function($q) use($warehouse_ids){
                                        if((count($this->warehouse_ids)!=0)){
                                            $q->where('warehouse_ids', 'REGEXP', $warehouse_ids);
                                        }
                                    })->select('*','product_name as name','product_id as id','product_created_at as created_at')
                                    ->orderBy('updated_at', 'desc')
                                    ->where('product_name', 'like', "%{$this->query}%")
                                    ->limit(5)->get()->toArray();
        
        // $Products = Product::whereHas('product_stock', function($q){
        //                         $q->whereIn('warehouse_id', $this->warehouse_ids);
        //                     })->select('id','slug','name','images','tax_ids','created_at')
        //                     ->where('name', 'like', "%{$this->query}%")
        //                     ->whereStatus('active')->limit(5)->get()->toArray();

        $this->products = array_map(function ($product) 
        {

            $rating_count = Review::whereProductId($product['id'])->count();
            $rating_sum = Review::whereProductId($product['id'])->sum('rating');
            $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $product['review_count'] = $rating_count;
            return $product;

        }, $Products);

    }


    public function productsuggestion(){
        $this->show_result = true;
    }

    public function unsetproductsuggestion(){
        $this->show_result = false;
    }

    public function render()
    {
        return view('livewire.ecommerce.layout.product-search');
    }
}
