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
use App\Models\ProductStock;

class QuickShop extends Component
{
    public $cart_product,$parent_attribute_id,$attributeIds,$parent_attribute_set_id,$product_id,$product_variant_id,$product_previous_variant_id,$quickshop_type;
    public $product_stock_id;
    public $cart_limit =0;
    public $available_quantity = 0;   
    public $parent_attribute = [];
    public $parent_available_variant_ids = [];
    public $attributes = [];
    public $selected_attributes_set_ids = [];
    public $warehouse_ids = [];
    public $image1,$stock_status,$price,$sale_price,$discount,$review,$review_count;
    
    protected $listeners = ['quickShop','updateAttributeSetId'];
    public $isLoading = true;
    
    public function mount(){
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
    }
    
    public function updatedParentAttributeSetId()
    {
        $this->parent_available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                        $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                            }
                                                                        });
                                                                    })->whereProductId($this->product_id)->whereAttributeSetId($this->parent_attribute_set_id)->pluck('product_variant_id')->toArray();
        $this->updateattribute($this->parent_attribute_set_id, $this->parent_available_variant_ids, $this->attributes, 'yes');
    }
    public function updateAttributeSetId($attribute_set_id){
        
        $attribute_ids = $this->attributeIds;
        
        $attribute_id = AttributeSet::where('id',$attribute_set_id)->pluck('attribute_id')->first();

        $key = array_search($attribute_id, $attribute_ids);
        $id = $this->product_id;
        unset($attribute_ids[$key]);

        $attributes = Attribute::find($attribute_ids)
                                ->each(function ($attribute, $key) use($id) {  
                                    $product_attribute_set_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                        $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                        }
                                                                                        });
                                                                                    })->whereProductId($id)
                                                                                    ->whereAttributeId($attribute->id)
                                                                                    ->pluck('attribute_set_id')->toArray();
                                    $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                                    ->each(function ($product_attribute_set, $key) use($id) {  
                                                                        $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                            $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                            }
                                                                            });
                                                                        })->whereProductId($id)
                                                                        ->whereAttributeSetId($product_attribute_set->id)
                                                                        ->pluck('product_variant_id')->toArray();
                                        });
                                })->toArray();
        $available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
            $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                $q1->whereIn('warehouse_id', $this->warehouse_ids)  ;
                                                                            }
            });
        })->whereProductId($this->product_id)->whereAttributeSetId($attribute_set_id)->pluck('product_variant_id')->toArray();
        $available_variant_ids = array_intersect($this->parent_available_variant_ids, $available_variant_ids);
        $this->updateattribute($attribute_set_id, $available_variant_ids, $attributes);

    }

    public function updateattribute($attribute_setId, $available_variant_ids, $attributes, $is_parent=''){

        $selected_attributes_set_ids = array_keys(array_filter(array_filter($this->selected_attributes_set_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        $veriy_selected_attribute_set_ids = [];
        $select_available_variant_id = '';
        foreach($available_variant_ids as $available_variant_id)
        {
            $match = 'yes';
            $selected_attribute_set_ids = [];

            if(empty($select_available_variant_id)){
                    
                foreach($attributes as $attribute){
                    $is_have_variant = 'no'; 
                    $attribute_set_id = '';
                    
                    foreach($attribute['sets'] as $set){
                        if(in_array($set['id'], $selected_attributes_set_ids) && in_array($available_variant_id, $set['available_variant_ids'])){
                            $is_have_variant = 'yes';
                            $attribute_set_id = $set['id'];
                        }
                        if(in_array($available_variant_id, $set['available_variant_ids']) && empty($attribute_set_id)){
                            $is_have_variant = 'yes';
                            $attribute_set_id = $set['id'];
                        }
                    }

                    if($is_have_variant=='no'){
                        $match = 'no';
                    }else{
                        $selected_attribute_set_ids[] = $attribute_set_id;
                    }
                }
                    
                if($match=='yes'){
                    $select_available_variant_id = $available_variant_id;
                    $veriy_selected_attribute_set_ids = $selected_attribute_set_ids;
                }

            }
            
        }

        $veriy_selected_attribute_set_ids[] = $attribute_setId;
        if(empty($is_parent)){
            $veriy_selected_attribute_set_ids[] = $this->parent_attribute_set_id;
        }

        $this->selected_attributes_set_ids = array_fill_keys($veriy_selected_attribute_set_ids, true);

        $this->calculatePrice();
    }
    
    public function calculatePrice()
    {
        $selected_attributes_set_ids = (array_keys(array_filter(array_filter($this->selected_attributes_set_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY))));

        $default = ProductVariant::select('id','cart_limit','product_id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                 ->where(function($q) use($selected_attributes_set_ids) {
                                    foreach($selected_attributes_set_ids as $set_id){
                                        $q->whereHas('product_attribute_set', function($q1) use($set_id){
                                            $q1->whereAttributeSetId($set_id);
                                        });
                                    }
                                 })->whereProductId($this->product_id)->first();
                                 
            
        if(isset($default)){

            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($default->id)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
            $price = $default->price;
            $discount = 0;
            $product = Product::select('tax_ids')->where('id',$default->product_id)->first();
    
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
            $this->price = $price;
            $this->sale_price = $sale_price;
            $this->discount = ($discount!=0)?(round($discount)):0;   
            $this->cart_limit = $default->cart_limit;
            $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $this->available_quantity = $product_stock->available_quantity??0;
            $this->product_stock_id = $product_stock->id??0; 
        
        }
        
    }

    public function quickShop($id,$variant_id,$quickshop_type='edit')
    {
        $this->quickshop_type = $quickshop_type;

        
        if(!empty($id)){

            if($quickshop_type=='replace'){
                $cart = Cart::find($id);
                $id = $this->product_id = $cart->product_id;
                $this->product_previous_variant_id = $cart->product_variant_id;
                $product = Product::select('id','slug','label_id','name','images','rating','stock_status','attribute_ids','tax_ids','created_at')->where('id',$this->product_id)->first();
                $default = ProductVariant::select('id','cart_limit','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                         ->where('id',$cart->product_variant_id)->first();
            }else{
                $this->product_id = $id;
                $this->product_variant_id = $variant_id;
                $product = Product::select('id','slug','label_id','name','images','rating','stock_status','attribute_ids','tax_ids','created_at')->where('id',$this->product_id)->first();
    
                $default = ProductVariant::where('id',$variant_id)->select('id','cart_limit','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                         ->whereProductId($product->id)->first();
            }
                                         
            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($default->id)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
                                            
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
            $this->price = $price;
            $this->sale_price = $sale_price;
            $this->discount = ($discount!=0)?(round($discount)):0;
            $this->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $this->review_count = $rating_count;
            $this->product_variant_id = $default->id;
            $this->cart_limit = $default->cart_limit;
            $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $this->available_quantity = $product_stock->available_quantity??0;
            $this->product_stock_id = $product_stock->id??0;
            
            $attribute_id = array_values(array_filter(explode(',',$product->attribute_ids)));   

            $this->parent_attribute_id = explode(',',array_shift($attribute_id));   

            $this->attributeIds = $attribute_id;

            $attribute_id = $attribute_id??[];

            $this->parent_attribute_set_id = ProductAttributeSet::whereProductVariantId($default->id)->whereAttributeId($this->parent_attribute_id)->pluck('attribute_set_id')->first();

            $this->parent_available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                            $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                            }
                                                                            });
                                                                        })->whereProductId($product->id)
                                                                        ->whereAttributeSetId($this->parent_attribute_set_id)
                                                                        ->pluck('product_variant_id')
                                                                        ->toArray();
           
            $this->parent_attribute = Attribute::find($this->parent_attribute_id)
                                                ->each(function ($attribute, $key) use($id) {  
                                                    $product_attribute_set_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                    $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                                            $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                                    }
                                                                                                    });
                                                                                                })->whereProductId($id)
                                                                                                ->whereAttributeId($attribute->id)
                                                                                                ->pluck('attribute_set_id')->toArray();
                                                    $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                                ->each(function ($product_attribute_set, $key) use($id) {  
                                                                    $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                                            $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                                                                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                                                            }
                                                                                                                            });
                                                                                                                        })->whereProductId($id)
                                                                                                                        ->whereAttributeSetId($product_attribute_set->id)
                                                                                                                        ->pluck('product_variant_id')->toArray();
                                                                });
                                                })->toArray();

            $this->attributes = Attribute::find($attribute_id)
                                            ->each(function ($attribute, $key) use($id) {  
                                                $product_attribute_set_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                                        $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                                }
                                                                                                });
                                                                                            })->whereProductId($id)
                                                                                            ->whereAttributeId($attribute->id)
                                                                                            ->pluck('attribute_set_id')->toArray();
                                                $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                ->each(function ($product_attribute_set, $key) use($id) {  
                                                    $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                            $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                                                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                                            }
                                                                                                            });
                                                                                                        })->whereProductId($id)
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
