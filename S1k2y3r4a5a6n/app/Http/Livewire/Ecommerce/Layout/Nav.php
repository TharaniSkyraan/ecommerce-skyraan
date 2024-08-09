<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\WishList;
use App\Models\Cart;
use App\Models\Review;
use App\Models\Tax;
use Carbon\Carbon;
use Auth;

class Nav extends Component
{
    public $show_result = false;
    public $query;
    public $products = [];
    public $warehouse_ids = [];
    protected $listeners = ['suggestion', 'unsetsuggestion','Getwishlist','addCartinUserCart'];

    public function mount()
    {

        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));

        $this->categories = Category::whereNULL('parent_id')->whereStatus('active')->orderBy('sort','asc')->get();
        
        if(count($this->categories)==0){
            $this->categories = Category::whereStatus('active')->orderBy('sort','asc')->get();
        }
        $count = Category::whereStatus('active')->count();
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
        
        $this->count = ($count!=0)?(round($count/$divide, 0)+1):0;
        $this->class_name = $class_name;
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

                if($default->sale_price!=0 && $default->discount_expired!='yes'){
                    
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

    public function suggestion(){
        $this->show_result = true;
    }

    public function unsetsuggestion(){
        $this->show_result = false;
    }
    public function addCartinUserCart($datas,$page=false)
    {
        if(\Auth::check()){
            
            foreach($datas as $data)
            { 
                
                if (!isset($data['product_id']) || !isset($data['product_variant_id'])) {
                    continue; // Skip this iteration if keys are missing
                }
    
                $cart = Cart::whereProductId($data['product_id'])
                            ->whereProductVariantId($data['product_variant_id'])
                            ->whereUserId(auth()->user()->id)->first();
                $data['user_id'] = auth()->user()->id;
                if(isset($cart)){
                    Cart::where('id',$cart->id)->update($data);
                }else{
                    Cart::create($data);
                }
            }
            $datas = Cart::whereUserId(auth()->user()->id)->orderBy('updated_at','desc')->get()->toArray();
            foreach($datas as $key => $data)
            {
                if(Product::where('id',$data['product_id'])->whereStatus('active')->doesntExist() || ProductVariant::where('id',$data['product_variant_id'])->doesntExist()){
                    $cart = Cart::where('id',$data['id'])->delete();
                    unset($datas[$key]);
                    $page = !empty($page)?$page:'refreshCart';
                }
            }
            if($page=='cartpage'){
                $this->emit('cartList');
            }elseif($page=='login' || $page=='refreshCart'){
                $this->emit('updateCart', $datas, $page);
            }elseif($page=='signup'){
                $this->emit('updateSignupCart', $datas);
            }else{
                $this->emit('updateCartQuantity', $datas);
            }
        }
    }
    
    public function render()
    { 
        return view('livewire.ecommerce.layout.nav');
    }

}