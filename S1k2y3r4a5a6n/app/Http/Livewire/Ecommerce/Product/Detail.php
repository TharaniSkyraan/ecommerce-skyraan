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

        $default = ProductVariant::select('id','cart_limit','price','images','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                 ->where(function($q) use($selected_attributes_set_ids) {
                                    foreach($selected_attributes_set_ids as $set_id){
                                        $q->whereHas('product_attribute_set', function($q1) use($set_id){
                                            $q1->whereAttributeSetId($set_id);
                                        });
                                    }
                                 })->whereProductId($this->product_id)->first();

        if(isset($default))
        {

            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($default->id)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
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

            $images = json_decode($default->images, true);
            $this->images = (count($images)!=0)?$images:json_decode($this->product['images'], true);
            $this->variant = $default->id;
            // $this->product_variant = encrypt($this->variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->product_variant = $this->variant;
            $this->price = $price;
            $this->sale_price = $sale_price;
            $this->discount = ($discount!=0)?(round($discount)):0;  
            $this->cart_limit = $default->cart_limit;
            $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $this->available_quantity = $product_stock->available_quantity??0;
            $this->product_stock_id = $product_stock->id??0;   
        
        }
        $this->emit('updateCarousel','');
        
    }
    
    public function updatedParentAttributeSetId()
    {
        $this->parent_available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                        $q->whereHas('product_stock', function($q1){
                                                                            $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                        });
                                                                    })->whereProductId($this->product_id)->whereAttributeSetId($this->parent_attribute_set_id)->pluck('product_variant_id')->toArray();
        $this->updateattribute($this->parent_attribute_set_id, $this->parent_available_variant_ids, $this->attributes, 'yes');
    }
    public function updateAttributesetId($attribute_set_id){
        
        $attribute_ids = $this->product['attribute_ids'];
        $attribute_id = AttributeSet::where('id',$attribute_set_id)->pluck('attribute_id')->first();

        $key = array_search($attribute_id, $attribute_ids);
        $id = $this->product['id'];
        unset($attribute_ids[$key]);

        $attributes = Attribute::find($attribute_ids)
                                ->each(function ($attribute, $key) use($id) {  
                                    $product_attribute_set_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                        $q->whereHas('product_stock', function($q1){
                                                                                            $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                        });
                                                                                    })->whereProductId($id)
                                                                                    ->whereAttributeId($attribute->id)
                                                                                    ->pluck('attribute_set_id')->toArray();
                                    $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                                    ->each(function ($product_attribute_set, $key) use($id) {  
                                                                        $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                            $q->whereHas('product_stock', function($q1){
                                                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                            });
                                                                        })->whereProductId($id)
                                                                        ->whereAttributeSetId($product_attribute_set->id)
                                                                        ->pluck('product_variant_id')->toArray();
                                        });
                                })->toArray();
        $available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
            $q->whereHas('product_stock', function($q1){
                $q1->whereIn('warehouse_id', $this->warehouse_ids);
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

        $product = Product::select('id','category_ids','slug','name','description','content','images','label_id','attribute_ids','related_product_ids','tax_ids','created_at')
                          ->whereSlug($this->slug)
                          ->where('created_at',$createdDate)
                          ->first();
        if(!isset($product)){
            abort(404);
        }
        $product = $product->toArray();
        
        $this->product_id = $id = $product['id'];
        if(!empty($this->variant) && $this->variant !=0){
            $default = ProductVariant::select('id as variant_id','cart_limit','images','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                    ->where('id',$this->variant)
                                    ->first()->toArray();
        }else{            
            $default = ProductVariant::select('id as variant_id','cart_limit','images','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                    ->whereIn('is_default', ['yes', 'no'])
                                    ->orderByRaw("is_default = 'yes' DESC")                                      
                                    ->whereProductId($product['id'])
                                    ->first()->toArray();
            $this->variant = $default['variant_id'];
            // $this->product_variant = encrypt($this->variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->product_variant = $this->variant;
        }
        
        $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($this->variant)
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();
                                            

        $discount = $price = $sale_price = 0;
      
        $label = Label::where('id',$product['label_id'])->whereStatus('active')->first();
        $rating_count = Review::whereProductId($product['id'])->count();
        $rating_sum = Review::whereProductId($product['id'])->sum('rating');

        $price = $default['price'];

        if($default['sale_price']!=0 && $default['discount_expired']!='yes')
        { 
            
            if($default['discount_duration']=='yes'){

                $currentDate = Carbon::now()->format('d-m-Y H:i');

                // Start and end date from user input or database
                $startDate = Carbon::parse($default['discount_start_date'])->format('d-m-Y H:i'); 
                $endDate = Carbon::parse($default['discount_end_date'])->format('d-m-Y H:i'); 

                // Validate start date
                if ($startDate <= $currentDate && $currentDate <= $endDate) {
                    $sale_price = $default['sale_price'];
                    $discount = (($price-$sale_price)/$price)*100;
                } 

            }else{
                $sale_price = $default['sale_price'];
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

        $order_item = OrderItem::whereHas('orders',function($q){
            $q->whereUserId(auth()->user()->id??0);
        })->whereProductId($product['id'])->first();

        $images = json_decode($default['images'], true);
        $this->images = (count($images)!=0)?$images:json_decode($product['images'], true);
        $this->price = $price;
        $this->sale_price = $sale_price;
        $this->discount = ($discount!=0)?(round($discount)):0;        
        $this->cart_limit = $default['cart_limit'];
        $this->stock_status = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
        $this->available_quantity = $product_stock->available_quantity??0;
        $this->product_stock_id = $product_stock->id??0;
        $category_ids = explode(',',$product['category_ids']);
        $this->category = Category::where('id',$category_ids[1]??'')->whereStatus('active')->first();
        
        $product['label'] = (isset($label->name))?$label->name:'';
        $product['label_color_code'] = (isset($label->color))?$label->color:'';
        $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
        $product['review_count'] = $rating_count;
        $product['is_purchased'] = isset($order_item)?'yes':'no';
        $product['is_reviewed'] = isset($order_item->review)?'yes':'no';
        $attribute_id = array_values(array_filter(explode(',',$product['attribute_ids'])));   

        $this->parent_attribute_id = explode(',',array_shift($attribute_id));    
        $product['attribute_ids'] = $attribute_id;
        $attribute_id = $attribute_id??[];

        $this->parent_attribute_set_id = ProductAttributeSet::whereProductVariantId($default['variant_id'])->whereAttributeId($this->parent_attribute_id)->pluck('attribute_set_id')->first();
        $this->parent_available_variant_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                    $q->whereHas('product_stock', function($q1){
                                                                        $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                    });
                                                                })->whereProductId($product['id'])
                                                                ->whereAttributeSetId($this->parent_attribute_set_id)
                                                                ->pluck('product_variant_id')->toArray();
        $this->parent_attribute = Attribute::find($this->parent_attribute_id)
                                            ->each(function ($attribute, $key) use($id) {  
                                                $product_attribute_set_ids = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                $q->whereHas('product_stock', function($q1){
                                                                                                    $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                                });
                                                                                            })->whereProductId($id)
                                                                                            ->whereAttributeId($attribute->id)
                                                                                            ->pluck('attribute_set_id')->toArray();
                                                $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                                ->each(function ($product_attribute_set, $key) use($id) {  
                                                    $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                            $q->whereHas('product_stock', function($q1){
                                                                                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
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
                                                                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                            });
                                                                                        })->whereProductId($id)
                                                                                        ->whereAttributeId($attribute->id)
                                                                                        ->pluck('attribute_set_id')->toArray();
                                            $attribute['sets'] = AttributeSet::find($product_attribute_set_ids)
                                            ->each(function ($product_attribute_set, $key) use($id) {  
                                                $product_attribute_set['available_variant_ids'] = ProductAttributeSet::whereHas('product_variant', function($q){
                                                                                                        $q->whereHas('product_stock', function($q1){
                                                                                                            $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                                                                                        });
                                                                                                    })->whereProductId($id)
                                                                                                    ->whereAttributeSetId($product_attribute_set->id)
                                                                                                    ->pluck('product_variant_id')->toArray();
                                            });
                                        })->toArray();

        $selected_attributes_set_ids = ProductAttributeSet::whereProductVariantId($default['variant_id'])->pluck('attribute_set_id')->toArray();
        $this->selected_attributes_set_ids = array_fill_keys($selected_attributes_set_ids, true);
        $this->product = $product;
        
        // Related product // you might also like
        $related_product_ids = array_unique(array_merge($related_product_ids, array_filter(explode(',',$product['related_product_ids']))));
        $this->productList('related_products',implode(',',$related_product_ids));

        
        // FREQUENTLY BOUGHT
        $frequently =  Product::leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                                ->select('products.id as id', \DB::raw('COUNT(order_items.product_id) as count'))
                                ->groupBy('id')
                                ->havingRaw('count != 0')
                                ->orderBy('count', 'desc')
                                ->limit(12)
                                ->pluck('id')->toArray();
                                
        $this->productList('frequently_bought_products',implode(',',$frequently));

        $this->buying_options  = BuyingOption::whereStatus('active')
                                ->where(function ($query) {
                                    $query->where('feature_type', '=', 'buying');
                                })
                                ->get()
                                ->toArray();  

        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;
        
        $zone = \Session::get('zone_config');
        $this->postal_code = $zone['postal_code']??'';
        $this->address_id = $zone['address_id']??0;

    }

    public function productList($type,$product_ids)
    {        

        $ids = explode(',',$product_ids);
        $Products = Product::whereHas('product_stock', function($q){
                                $q->whereIn('warehouse_id', $this->warehouse_ids);
                            })->select('id','slug','name','images','label_id','tax_ids','created_at')
                            ->whereIn('id',$ids)
                            ->get()
                            ->sortBy(function ($product) use ($ids) {
                                return array_search($product->id, $ids);
                            })->toArray();
        $this->$type = array_map(function ($product) 
        {

            $default = ProductVariant::select('id','price','sale_price','is_default','cart_limit','discount_expired','discount_start_date','discount_end_date','discount_duration')
                                    // ->whereIsDefault('yes')    
                                    ->whereIn('is_default', ['yes', 'no'])
                                    ->orderByRaw("is_default = 'yes' DESC")                                  
                                    ->whereProductId($product['id'])->first();
            $discount = $price = $sale_price = 0;
    
            $label = Label::where('id',$product['label_id'])->whereStatus('active')->first();
            $rating_count = Review::whereProductId($product['id'])->count();
            $rating_sum = Review::whereProductId($product['id'])->sum('rating');

            if(isset($default))
            {
                $product_stock = ProductStock::select('id', 'available_quantity')
                                            ->whereIn('warehouse_id',$this->warehouse_ids)
                                            ->whereProductVariantId($default->id)
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
                            $discount = ($default->sale_price/$default->price)*100;
                        } 

                    }else{
                        $sale_price = $default->sale_price;
                        $discount = ($default->sale_price/$default->price)*100;
                    }
                    
                }
                if(!empty($product['tax_ids']))
                {
                    if($tax = Tax::where('id',$product['tax_ids'])->where('status','active')->first())
                    {                    
                        $price = $price + ($tax->percentage * ($price / 100));
                        $sale_price = $sale_price + ($tax->percentage * ($sale_price / 100));
                    }
                }

            }

            $order_item = OrderItem::whereHas('orders',function($q){
                $q->whereUserId(auth()->user()->id??0);
            })->whereProductId($product['id'])->first();

            $images = json_decode($product['images'], true);
            $product['image1'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
            $product['image2'] = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
            $product['price'] = $price;
            $product['slug'] = $product['slug'];
            $product['variant_id'] = $default->id??0;
            $product['sale_price'] = $sale_price;
            $product['discount'] = ($discount!=0)?(round($discount)):0;
            $product['label'] = (isset($label->name))?$label->name:'';
            $product['label_color_code'] = (isset($label->color))?$label->color:'';
            $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $product['review_count'] = $rating_count;
            $product['is_purchased'] = isset($order_item)?'yes':'no';
            $product['is_reviewed'] = isset($order_item->review)?'yes':'no';
            $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
            $product['available_quantity'] = $product_stock->available_quantity??0;
            $product['product_stock_id'] = $product_stock->id??0;
            $product['cart_limit'] = $default->cart_limit??0;
            $product['product_type'] = '';
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
