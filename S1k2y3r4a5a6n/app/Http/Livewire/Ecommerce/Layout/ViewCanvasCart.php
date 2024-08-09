<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeSet;
use App\Models\ProductAttributeSet;
use App\Models\Tax;
use App\Models\UserCart;
use App\Models\ProductStock;
use App\Models\Cart;
use Carbon\Carbon;

class ViewCanvasCart extends Component
{

    public $cart_products = [];

    public $related_products = [];

    public $warehouse_ids = [];

    public $total_price = 0;
    
    protected $listeners = ['MyCart','RemoveProductFromCart'];

    public function mount(){        
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
    }

    public function MyCart($datas)
    {
        $cart_products = [];
        
        $related_product_ids = [];

        $total_price = 0;

        foreach($datas as $key => $data)
        {
            if(!isset($data['product_id'])){
                unset($datas[$key]);
            }else
            if(Product::where('id',$data['product_id'])->whereStatus('active')->exists() && ProductVariant::where('id',$data['product_variant_id'])->exists()){
         
                $product = Product::where('id',$data['product_id'])->select('id','slug','name','images','related_product_ids','tax_ids','created_at')->first()->toArray();
                $default = ProductVariant::where('id',$data['product_variant_id'])->first();

                $discount = $price = $sale_price = 0;
                
                if(isset($default))
                {
                    $product_stock = ProductStock::select('id', 'available_quantity')
                                                ->whereIn('warehouse_id',$this->warehouse_ids)
                                                ->whereProductVariantId($data['product_variant_id'])
                                                ->groupBy('id', 'available_quantity')
                                                ->orderBy('available_quantity','desc')
                                                ->first();
                   
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

                    $images = json_decode($product['images'], true);
                    $product['image'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                    $product['variant_id'] = $data['product_variant_id'];
                    $product['slug'] = $product['slug'];
                    $product['price'] = $price;
                    $product['sale_price'] = $sale_price;
                    $product['discount'] = ($discount!=0)?(round($discount)):0;
                    $product['quantity'] = $data['quantity'];
                    $product['cart_limit'] = $default->cart_limit;
                    $product['attributes'] = $default->getSetAttribute();
                    $product['total_price'] = (($discount!=0)?($data['quantity']*$sale_price):($data['quantity']*$price));
                    $total_price += $product['total_price'];
                    $product['product_type'] = ProductVariant::whereProductId($data['product_id'])->count();
                    $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity>=$data['quantity'])?'in_stock':'out_of_stock'):'out_of_stock';
                    $product['available_quantity'] = $product_stock->available_quantity??0;
                    $product['product_stock_id'] = $product_stock->id??0;
                    $cart_products[] = $product;
                
                    $related_product_ids = array_unique(array_merge($related_product_ids, array_filter(explode(',',$product['related_product_ids']))));
                
                }
            }else{
                unset($datas[$key]);
                $this->RemoveProductFromCart($key);
            }
        }
        $this->total_price = $total_price;
        $this->cart_products = $cart_products;

        $this->related_products = Product::whereHas('product_stock', function($q1){
                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                         })->limit(10)->find($related_product_ids)->toArray();
        $this->emit('cartCount',count($cart_products),$total_price);
    }
    
    public function RemoveProductFromCart($index){

        if(\Auth::check()){
            $product  = explode('-',$index);
            $cart = Cart::whereProductId($product[0])
                        ->whereProductVariantId($product[1])
                        ->whereUserId(auth()->user()->id)->delete();
            if(UserCart::whereUserId(auth()->user()->id)->count()==0){
                UserCart::whereUserId(auth()->user()->id)->delete();
            }
        }
    }


    public function render()
    {
        return view('livewire.ecommerce.layout.view-canvas-cart');
    }
}
