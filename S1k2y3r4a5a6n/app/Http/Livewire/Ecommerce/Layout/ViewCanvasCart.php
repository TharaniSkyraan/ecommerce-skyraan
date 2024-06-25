<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeSet;
use App\Models\ProductAttributeSet;
use App\Models\Tax;
use App\Models\UserCart;
use App\Models\Cart;
use Carbon\Carbon;

class ViewCanvasCart extends Component
{

    public $cart_products = [];

    public $related_products = [];

    public $total_price = 0;

    protected $listeners = ['MyCart','updatePrice','RemoveProductFromCart'];

    public function MyCart($datas)
    {
        $cart_products = [];
        
        $related_product_ids = [];

        $total_price = 0;

        foreach($datas as $key => $data)
        {
            if(Product::where('id',$data['product_id'])->exists() && ProductVariant::where('id',$data['product_variant_id'])->exists()){
         
                $product = Product::where('id',$data['product_id'])->select('id','slug','name','images','related_product_ids','tax_ids','created_at')->first()->toArray();
                $default = ProductVariant::where('id',$data['product_variant_id'])->select('price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration')->first();

                $discount = $price = $sale_price = 0;
                
                if(isset($default))
                {
                    
                    $attribute_set_ids = ProductAttributeSet::whereProductVariantId($data['product_variant_id'])->pluck('attribute_set_id')->toArray();
                    $attribute_set_name = AttributeSet::find($attribute_set_ids)->pluck('name')->toArray();

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
                                $discount = ($sale_price/$price)*100;
                            } 

                        }else{
                            $sale_price = $default->sale_price;
                            $discount = ($sale_price/$price)*100;
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
                    $product['discount'] = ($discount!=0)?(100 - round($discount)):0;
                    $product['quantity'] = $data['quantity'];
                    $product['attributes'] = implode('-',$attribute_set_name);
                    $product['total_price'] = (($discount!=0)?($data['quantity']*$sale_price):($data['quantity']*$price));
                    $total_price += $product['total_price'];
                    $product['product_type'] = ProductVariant::whereProductId($data['product_id'])->count();
                
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

        $this->related_products = Product::limit(10)->find($related_product_ids)->toArray();

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
