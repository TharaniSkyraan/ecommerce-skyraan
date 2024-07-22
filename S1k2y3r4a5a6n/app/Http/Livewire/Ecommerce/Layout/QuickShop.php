<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\ProductAttributeSet;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Tax;
use App\Models\Review;

class QuickShop extends Component
{
    public $cart_product,$parent_attribute_id,$parent_attribute_set_id,$product_id,$product_variant_id,$product_previous_variant_id,$quickshop_type;
    public $parent_attribute = [];
    public $parent_available_variant_ids = [];
    public $attributes = [];
    public $selected_attributes_set_ids = [];
    public $image1,$stock_status,$price,$sale_price,$discount,$review,$review_count;
    
    protected $listeners = ['quickShop'];
    public $isLoading = true;
    

    public function updatedParentAttributeSetId(){
        $this->parent_available_variant_ids = ProductAttributeSet::whereProductId($this->product_id)->whereAttributeSetId($this->parent_attribute_set_id)->pluck('product_variant_id')->toArray();
        $selected_attributes_set_ids = array_keys(array_filter(array_filter($this->selected_attributes_set_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        $arrays = array_diff($selected_attributes_set_ids, [$this->parent_attribute_set_id]);
        $veriy_selected_attribute_set_ids = [];
        foreach($this->attributes as $attribute){
            $is_have_variant = 'no'; 
            $variant_attribute_set_id = [];

            foreach($attribute['sets'] as $set){
                if(in_array($set['id'], $selected_attributes_set_ids) && count(array_intersect($this->parent_available_variant_ids, $set['available_variant_ids']))!=0){
                    $is_have_variant = 'yes';
                    $veriy_selected_attribute_set_ids[] = $set['id'];
                }
                if(count(array_intersect($this->parent_available_variant_ids, $set['available_variant_ids']))!=0){
                    $variant_attribute_set_id[] = $set['id'];
                }
            }

            if($is_have_variant=='no'){
                $veriy_selected_attribute_set_ids[] = $variant_attribute_set_id[0];
            }
        }
        $veriy_selected_attribute_set_ids[] = $this->parent_attribute_set_id;

        $this->selected_attributes_set_ids = array_fill_keys($veriy_selected_attribute_set_ids, true);

        $this->calculatePrice();
    }

    public function updatedSelectedAttributesSetIds(){

        $this->calculatePrice();
    }
    
    public function calculatePrice()
    {
        $selected_attributes_set_ids = (array_keys(array_filter(array_filter($this->selected_attributes_set_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY))));

        $default = ProductVariant::select('id','prodcut_id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                 ->where(function($q) use($selected_attributes_set_ids) {
                                    foreach($selected_attributes_set_ids as $set_id){
                                        $q->whereHas('product_attribute_set', function($q1) use($set_id){
                                            $q1->whereAttributeSetId($set_id);
                                        });
                                    }
                                 })->whereProductId($this->product_id)->first();
                                 
        if(isset($default)){

            $price = $default->price;
            $discount = 0;
            $product = Product::select('tax_ids')->where('id',$default->prodcut_id)->first();
    
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
                        $discount = ($default->sale_price/$default->price)*100;
                    } 
    
                }else{
                    $sale_price = $default->sale_price;
                    $discount = ($default->sale_price/$default->price)*100;
                }
                
            }
            
            if(!empty($product->tax_ids))
            {
                if($tax = Tax::where('id',$product->tax_ids)->where('status','active')->first())
                {                    
                    $price = $price + ($tax->percentage * ($price / 100));
                    if($sale_price!=0){
                        $sale_price = $sale_price + ($tax->percentage * ($sale_price / 100));
                    }                    
                }
            }
            $this->product_variant_id = $default->id;
            $this->stock_status = $default->stock_status;
            $this->price = $price;
            $this->sale_price = $sale_price;
            $this->discount = ($discount!=0)?(round($discount)):0;    
        
        }
        
      
    }

    public function quickShop($id='',$quickshop_type='edit')
    {
        $this->quickshop_type = $quickshop_type;

        
        if(!empty($id)){

            if($quickshop_type=='replace'){
                $cart = Cart::find($id);
                $id = $this->product_id = $cart->product_id;
                $this->product_previous_variant_id = $cart->product_variant_id;
                $product = Product::select('id','label_id','name','images','rating','stock_status','attribute_ids','tax_ids')->where('id',$this->product_id)->first();
                $default = ProductVariant::select('id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                         ->where('id',$cart->product_variant_id)->first();
            }else{
                $this->product_id = $id;
                $product = Product::select('id','label_id','name','images','rating','stock_status','attribute_ids','tax_ids')->where('id',$this->product_id)->first();
    
                $default = ProductVariant::select('id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                                ->whereIsDefault('yes')                                     
                                                ->whereProductId($product->id)->first();
            }
                                            
            $discount = $price = $sale_price = 0;
    
            $rating_count = Review::whereProductId($product->id)->count();
            $rating_sum = Review::whereProductId($product->id)->sum('rating');
    
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
                
                if(!empty($product->tax_ids))
                {
                    if($tax = Tax::where('id',$product->tax_ids)->where('status','active')->first())
                    {                    
                        $price = $price + ($tax->percentage * ($price / 100));
                        if($sale_price!=0){
                            $sale_price = $sale_price + ($tax->percentage * ($sale_price / 100));
                        }                    
                    }
                }
                
            }
    
            $images = json_decode($product->images, true);
            $this->image1 = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
            $this->stock_status = $default->stock_status;
            $this->price = $price;
            $this->sale_price = $sale_price;
            $this->discount = ($discount!=0)?(round($discount)):0;
            $this->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $this->review_count = $rating_count;
            $this->product_variant_id = $default->id;
            
            $attribute_id = array_values(array_filter(explode(',',$product->attribute_ids)));   

            $this->parent_attribute_id = explode(',',array_shift($attribute_id));    
            $attribute_id = $attribute_id??[];

            $this->parent_attribute_set_id = ProductAttributeSet::whereProductVariantId($default->id)->whereAttributeId($this->parent_attribute_id)->pluck('attribute_set_id')->first();
           
            $this->parent_available_variant_ids = ProductAttributeSet::whereProductId($product->id)->whereAttributeSetId($this->parent_attribute_set_id)->pluck('product_variant_id')->toArray();
             $this->parent_attribute = Attribute::find($this->parent_attribute_id)
                                                ->each(function ($attribute, $key) use($id) {  
                                                    $product_attribute_set_ids = ProductAttributeSet::whereProductId($id)
                                                                                                ->whereAttributeId($attribute->id)
                                                                                                ->pluck('attribute_set_id')->toArray();
                                                    $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                    ->each(function ($product_attribute_set, $key) use($id) {  
                                                        $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereProductId($id)
                                                                                                            ->whereAttributeSetId($product_attribute_set->id)
                                                                                                            ->pluck('product_variant_id')->toArray();
                                                    });
                                                })->toArray();
            $this->attributes = Attribute::find($attribute_id)
                                            ->each(function ($attribute, $key) use($id) {  
                                                $product_attribute_set_ids = ProductAttributeSet::whereProductId($id)
                                                                                            ->whereAttributeId($attribute->id)
                                                                                            ->pluck('attribute_set_id')->toArray();
                                                $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                ->each(function ($product_attribute_set, $key) use($id) {  
                                                    $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereProductId($id)
                                                                                                        ->whereAttributeSetId($product_attribute_set->id)
                                                                                                        ->pluck('product_variant_id')->toArray();
                                                });
                                            })->toArray();
            $product->cart_id = $cart->id??'';
            $selected_attributes_set_ids = ProductAttributeSet::whereProductVariantId($default->id)->pluck('attribute_set_id')->toArray();
            $this->selected_attributes_set_ids = array_fill_keys($selected_attributes_set_ids, true);
            $this->cart_product = $product;
        } 

    }

    public function render()
    {        
        return view('livewire.ecommerce.layout.quick-shop');
    }
}
