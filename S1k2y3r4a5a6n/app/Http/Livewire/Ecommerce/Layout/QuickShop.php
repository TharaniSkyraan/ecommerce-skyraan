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
use App\Models\ProductSearches;
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
                    $q1->whereIn('warehouse_id', $this->warehouse_ids) ;
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

        $product = ProductSearches::whereProductId($this->product_id);
        foreach($selected_attributes_set_ids as $set_id){
            $product->whereRaw("FIND_IN_SET(?, attribute_set_ids)", [$set_id]);
        }
        $product = $product->first();
                                 
            
        if(isset($product)){

            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($product->variant_id)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
        
            $this->cart_product->name = $product->product_name;
            $this->product_variant_id = $product->variant_id; 
            $this->price = $product->price;
            $this->sale_price = $product->sale_price;
            $this->discount = ($product->discount!=0)?(round($product->discount)):0;  
            $this->cart_limit = $product->cart_limit;        
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

                $product = ProductSearches::whereVariantId($cart->product_variant_id)->first();

                // $product = Product::select('id','slug','label_id','name','images','rating','stock_status','attribute_ids','tax_ids','created_at')->where('id',$this->product_id)->first();
                // $default = ProductVariant::select('id','cart_limit','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                //                          ->where('id',$cart->product_variant_id)->first();
            }else{
                $this->product_variant_id = $variant_id;

                $product = ProductSearches::whereVariantId($variant_id)->first();

                // $product = Product::select('id','slug','label_id','name','images','rating','stock_status','attribute_ids','tax_ids','created_at')->where('id',$this->product_id)->first();
                // $default = ProductVariant::where('id',$variant_id)->select('id','cart_limit','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                //                          ->whereProductId($this->product_id)->first();
            }

            $this->product_id = $product->product_id;                    
            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($product->variant_id)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
                                            
    
            $rating_count = Review::whereProductId($this->product_id)->count();
            $rating_sum = Review::whereProductId($this->product_id)->sum('rating');
    
            $images = json_decode($product->images, true);
            $this->image1 = $product->image1;
            $this->price = $product->price;
            $this->sale_price = $product->sale_price;
            $this->discount = ($product->discount!=0)?(round($product->discount)):0;
            $this->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $this->review_count = $rating_count;
            $this->product_variant_id = $product->variant_id;
            $this->cart_limit = $product->cart_limit;
            $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $this->available_quantity = $product_stock->available_quantity??0;
            $this->product_stock_id = $product_stock->id??0;
            
            $attribute_id = array_values(array_filter(explode(',',$product->product->attribute_ids)));   

            $this->parent_attribute_id = explode(',',array_shift($attribute_id));   

            $this->attributeIds = $attribute_id;

            $attribute_id = $attribute_id??[];

            $this->parent_attribute_set_id = ProductAttributeSet::whereProductVariantId($product->variant_id)->whereAttributeId($this->parent_attribute_id)->pluck('attribute_set_id')->first();

            $this->parent_available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                            $q->whereHas('product_stock', function($q1){
                                                                            if((count($this->warehouse_ids)!=0)){
                                                                                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                            }
                                                                            });
                                                                        })->whereProductId($this->product_id)
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
            $product->name = $product->product_name;
            $selected_attributes_set_ids = ProductAttributeSet::whereProductVariantId($product->variant_id)->pluck('attribute_set_id')->toArray();
            $this->selected_attributes_set_ids = array_fill_keys($selected_attributes_set_ids, true);
            $this->cart_product = $product;
        } 

    }

    public function render()
    {        
        return view('livewire.ecommerce.layout.quick-shop');
    }
}
