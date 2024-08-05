<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Tax;
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
        
        $Products = Product::whereHas('product_stock', function($q){
                                $q->whereIn('warehouse_id', $this->warehouse_ids);
                            })->select('id','slug','name','images','tax_ids','created_at')
                            ->where('name', 'like', "%{$this->query}%")
                            ->whereStatus('active')->limit(5)->get()->toArray();

        $this->products = array_map(function ($product) 
        {

            $default = ProductVariant::whereHas('product_stock', function($q){
                                        $q->whereIn('warehouse_id', $this->warehouse_ids);
                                    })->select('price','sale_price','discount_expired','is_default','discount_start_date','discount_end_date','discount_duration')
                                    // ->whereIsDefault('yes')   
                                    ->whereIn('is_default', ['yes', 'no'])
                                    ->orderByRaw("is_default = 'yes' DESC")                                     
                                    ->whereProductId($product['id'])->first();
            $discount = $price = $sale_price = 0;

            $rating_count = Review::whereProductId($product['id'])->count();
            $rating_sum = Review::whereProductId($product['id'])->sum('rating');

            if(isset($default))
            {

                $price = $default->price;

                if($default->sale_price!=0 && $default->discount_expired!='yes')
                {                
                    
                    if($default->discount_duration=='yes'){

                        $currentDate = Carbon::now()->format('d-m-Y H:i');

                        // Start and end date from user input or database
                        $startDate = Carbon::parse($default->discount_start_date)->format('d-m-Y H:i'); 
                        $endDate = Carbon::parse($default->discount_end_date)->format('d-m-Y H:i'); 

                        // Validate start date
                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                            $sale_price = $default->sale_price;
                            $discount = (($price-$sale_price)/$price)*100;
                        } 

                    }else{
                        $sale_price = $default->sale_price;
                        $discount = (($price-$sale_price)/$price)*100;
                    }
                    
                }
                if(!empty($product['tax_ids']))
                {
                    if($tax = Tax::where('id',$product['tax_ids'])->where('status','active')->first())
                    {                    
                        $price = $price + ($tax->percentage * ($price / 100));
                        if($sale_price!=0){
                            $sale_price = $sale_price + ($tax->percentage * ($sale_price / 100));
                        }                    
                    }
                }
            }

            $images = json_decode($product['images'], true);
            $product['image'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
            $product['slug'] = $product['slug'];
            $product['price'] = $price;
            $product['sale_price'] = $sale_price;
            $product['discount'] = ($discount!=0)?(round($discount)):0;
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
