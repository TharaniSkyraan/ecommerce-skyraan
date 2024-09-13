<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\ProductAttributeSet;
use App\Models\Label;
use App\Models\Review;
use App\Models\User;
use App\Models\WishList;
use App\Models\BuyingOption;
use App\Models\OrderItem;
use App\Models\SavedAddress;
use App\Models\UserCart;
use App\Models\Category;
use App\Models\Tax;
use Carbon\Carbon;
use App\Traits\ZoneConfig;
use App\Models\ProductStock;
use App\Models\ProductSearches;

class Detail extends Component
{
    use ZoneConfig;

    public $product,$parent_attribute_id,$parent_attribute_set_id,$product_id,$slug,$product_variant,$product_stock_id,$category;
    public $parent_attribute = [];
    public $parent_available_variant_ids = [];
    public $cart_limit =0;
    public $available_quantity = 0;    
    public $attributes = [];
    public $warehouse_ids = [];
    public $selected_attributes_set_ids = [];
    public $postal_code, $postal_code1;
    public $isenabletoggle = false;
    
    public $images,$stock_status,$price,$sale_price,$discount,$variant,$prdRef;
    
    public $tab='description';

    protected $queryString = ['product_variant','tab','prdRef'];

    public $wishlist = [];
    
    protected $listeners = ['addremoveWish','updateAttributesetId'];

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

        if(isset($product))
        {

            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($product->variant_id)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
           
            $this->product['name'] = $product->product_name;
            $this->images = json_decode($product->images, true);
            $this->variant = $product->variant_id;
            // $this->product_variant = encrypt($this->variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->product_variant = $this->variant;
            $this->price = $product->price;
            $this->sale_price = $product->sale_price;
            $this->discount = ($product->discount!=0)?(round($product->discount)):0;  
            $this->cart_limit = $product->cart_limit;
            $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $this->available_quantity = $product_stock->available_quantity??0;
            $this->product_stock_id = $product_stock->id??0;   
            $product = $product->append(['order_item'])->toArray();
            $this->product['is_purchased'] = isset($product['order_item'])?'yes':'no';
            $this->product['is_reviewed'] = isset($product['order_item']['review'])?'yes':'no';
        
        }
        $this->emit('updateCarousel','');
        
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
    public function updateAttributesetId($attribute_set_id){
        
        $attribute_ids = $this->product['attribute_ids'];
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
                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
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

    public function mount($slug)
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));

        $createdDate = Carbon::parse($this->prdRef);
        // $this->variant = !empty($this->product_variant)?decrypt($this->product_variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213'):'';
        $this->variant = $this->product_variant??'';
        $this->slug = $slug;
        
        $related_product_ids = [];

        $product = ProductSearches::whereSlug($this->slug)->where('product_created_at',$createdDate);
       

        if(!empty($this->variant) && $this->variant !=0){
            $product = $product->where('variant_id',$this->variant)->first();
        }else{            
            $product = $product->first();
            $this->variant = $product->variant_id;
            // $this->product_variant = encrypt($this->variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->product_variant = $this->variant;
        }
        
        if(!isset($product)){
            abort(404);
        }
        $this->product_id = $id = $product->product_id;
        
        $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($this->variant)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
                            
        $rating_count = Review::whereProductId($this->product_id)->count();
        $rating_sum = Review::whereProductId($this->product_id)->sum('rating');

        $order_item = OrderItem::whereHas('orders',function($q){
                                    $q->whereUserId(auth()->user()->id??0);
                                })->whereProductId($this->product_id)->first();

        $this->images = json_decode($product->images, true);
        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->discount = ($product->discount!=0)?(round($product->discount)):0;        
        $this->cart_limit = $product->cart_limit;
        $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
        $this->available_quantity = $product_stock->available_quantity??0;
        $this->product_stock_id = $product_stock->id??0;
        $category_ids = explode(',',$product->category_ids);
        $this->category = Category::where('id',$category_ids[1]??'')->whereStatus('active')->first();
        
        $attribute_id = array_values(array_filter(explode(',',$product->product->attribute_ids)));   

        $this->parent_attribute_id = explode(',',array_shift($attribute_id));    
        $product->attribute_ids = $attribute_id;
        $attribute_id = $attribute_id??[];
        
        $this->parent_attribute_set_id = ProductAttributeSet::whereProductVariantId($product->variant_id)->whereAttributeId($this->parent_attribute_id)->pluck('attribute_set_id')->first();
        $this->parent_available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                    $q->whereHas('product_stock', function($q1){
                                                                        if((count($this->warehouse_ids)!=0)){
                                                                            $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                        }
                                                                    });
                                                                })->whereProductId($id)
                                                                ->whereAttributeSetId($this->parent_attribute_set_id)
                                                                ->pluck('product_variant_id')->toArray();
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

        $selected_attributes_set_ids = ProductAttributeSet::whereProductVariantId($product->variant_id)->pluck('attribute_set_id')->toArray();
        $this->selected_attributes_set_ids = array_fill_keys($selected_attributes_set_ids, true);
        
        
        // Related product // you might also like
        $related_product_ids = array_unique(array_merge($related_product_ids, array_filter(explode(',',$product->product->related_product_ids))));
        $this->productList('related_products',implode(',',$related_product_ids));
        
        // FREQUENTLY BOUGHT                                
        $this->productList('frequently_bought_products','');

        $this->buying_options  = BuyingOption::whereStatus('active')
                                ->where('feature_type', '!=', 'product')
                                ->get()
                                ->toArray(); 

        $product = $product->append(['order_item'])->toArray();
        $product['is_purchased'] = isset($product['order_item'])?'yes':'no';
        $product['is_reviewed'] = isset($product['order_item']['review'])?'yes':'no';
        $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
        $product['review_count'] = $rating_count;
        $product['name'] = $product['product_name'];
        $this->product = $product;

        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;
        
        $zone = \Session::get('zone_config');
        $this->postal_code = $zone['postal_code']??'';
        $this->address_id = $zone['address_id']??0;

    }

    public function productList($type,$product_ids)
    {        

        if($type=='frequently_bought_products')
        {
            $Products = ProductSearches::whereStatus('active')->get()
                                    ->each(function ($items) {
                                        $items->append(['order_item']);
                                        return $items;
                                    })->toArray();
                                                    
            $Products = array_filter($Products, function($frequently) {
                return $frequently['order_item'] !== null;
            });

        }else{

            $warehouse_ids = (count($this->warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $this->warehouse_ids)) . ')(,|$)':'';
            $ids = explode(',',$product_ids);
            $Products = ProductSearches::whereStatus('active')
                                        ->whereIn('product_id',$ids)
                                        ->where('warehouse_ids', 'REGEXP', $warehouse_ids)
                                        ->get()->toArray();
        }

        $this->$type = array_map(function ($product) 
        {

            $rating_count = Review::whereProductId($product['product_id'])->count();
            $rating_sum = Review::whereProductId($product['product_id'])->sum('rating');

            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($product['variant_id'])
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();

            $order_item = OrderItem::whereHas('orders',function($q){
                                        $q->whereUserId(auth()->user()->id??0);
                                    })->whereProductId($product['product_id'])->first();

            $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $product['review_count'] = $rating_count;
            $product['name'] = $product['product_name'];
            $product['id'] = $product['product_id'];
            $product['created_at'] = $product['product_created_at'];
            $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $product['available_quantity'] = $product_stock->available_quantity??0;
            $product['product_stock_id'] = $product_stock->id??0;
            return $product;

        }, $Products);

    }

    public function addremoveWish($product_id)
    {
        if(\Auth::check()){
            $wishlistadded = Wishlist::whereUserId(auth()->user()->id)->where('product_ids', 'REGEXP', $product_id)->first();
           
            if(!isset($wishlistadded)){ 
                $product_ids = array_filter(array_values($this->wishlist));
                array_push($product_ids, $product_id);
                $this->wishlist = $product_ids;
                $wishlist['product_ids'] = ','.implode(',', $product_ids).',';
                $wishlist['user_id'] = auth()->user()->id;

                Wishlist::updateOrCreate(
                    ['user_id' => auth()->user()->id],
                    $wishlist
                );
            }else{
                $product_ids = array_filter(array_values($this->wishlist));
                $product_ids = array_diff($product_ids, [$product_id]);
                $this->wishlist = $product_ids;
                $wishlist['product_ids'] = ','.implode(',', $product_ids).',';
                $wishlist['user_id'] = auth()->user()->id;

                Wishlist::updateOrCreate(
                    ['user_id' => auth()->user()->id],
                    $wishlist
                );
            }
        }
    }

    public function enablechangepincode(){
        $this->isenabletoggle = ($this->isenabletoggle)?false:true;
    }
    
    public function checkpincode()
    {

        $this->resetValidation();
        $ipData = \Session::get('ip_config');
        $data = array(
            'address_id' => '',
            'city' => '',
            'latitude' => '',
            'longitude' => '',
            'postal_code' => $this->postal_code1??''
        );  
        $result = $this->configzone($data); 
        $this->validate([
            'postal_code1' => ['required','postal_code:'.($ipData->code??'IN'), function ($attribute, $value, $fail) use($result) {
                if(empty($result['zone_id'])) {
                    $fail('Delivery is not available for this location.');
                }
            }]
        ], [
            'postal_code1.required' => 'Postal code is required',
            'postal_code1.postal_code' => 'Please enter valid postal code'
        ]);

        $this->postal_code = $this->postal_code1;
        session(['zone_config' => $result]);
        view()->share('zone_data',\Session::get('zone_config'));
        $this->enablechangepincode();

    }

    public function changepincode($address_id)
    {
        $this->resetValidation();
        $this->reset(['postal_code1']);
        $address = SavedAddress::find($address_id);

        $data = array(
            'address_id' => $address->id??'',
            'city' => $address->city??'',
            'latitude' => '',
            'longitude' => '',
            'postal_code' => $address->postal_code??''
        );  
        $this->postal_code = $address->postal_code;
        $this->address_id = $address->id;
        $validateData['user_address_id'] = $address->id;
        $validateData['postal_code'] = $address->postal_code;

        UserCart::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $validateData
        );
        
        $result = $this->configzone($data); 
        session(['zone_config' => $result]);
        view()->share('zone_data',\Session::get('zone_config'));
        $this->enablechangepincode();
    }
    
    public function checkout()
    {
        return redirect()->to('checkout');
    }
    
    public function render()
    {
        return view('livewire.ecommerce.product.detail');
    }
}
