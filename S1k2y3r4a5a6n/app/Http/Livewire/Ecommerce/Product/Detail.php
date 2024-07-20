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
use App\Models\Tax;
use Carbon\Carbon;

class Detail extends Component
{
    
    public $product,$parent_attribute_id,$parent_attribute_set_id,$product_id,$slug,$product_variant;
    public $parent_attribute = [];
    public $parent_available_variant_ids = [];
    public $attributes = [];
    public $selected_attributes_set_ids = [];
    
    public $images,$stock_status,$price,$sale_price,$discount,$variant,$prdRef;
    
    public $tab='description';

    protected $queryString = ['product_variant','tab','prdRef'];

    public $wishlist = [];

    public function calculatePrice()
    {
        $selected_attributes_set_ids = (array_keys(array_filter(array_filter($this->selected_attributes_set_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY))));

        $default = ProductVariant::select('id','price','images','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
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

            $images = json_decode($default->images, true);
            $this->images = (count($images)!=0)?$images:json_decode($this->product['images'], true);
            $this->variant = $default->id;
            // $this->product_variant = encrypt($this->variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->product_variant = $this->variant;
            $this->stock_status = $default->stock_status;
            $this->price = $price;
            $this->sale_price = $sale_price;
            $this->discount = ($discount!=0)?(100 - round($discount)):0;    
        
        }
        
    }
    
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

    public function mount($slug)
    {
        $createdDate = Carbon::parse($this->prdRef);
        // $this->variant = !empty($this->product_variant)?decrypt($this->product_variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213'):'';
        $this->variant = $this->product_variant??'';
        $this->slug = $slug;
        
        $related_product_ids = [];

        $product = Product::select('id','slug','name','description','content','images','label_id','attribute_ids','related_product_ids','tax_ids','created_at')
                          ->whereSlug($this->slug)
                          ->where('created_at',$createdDate)
                          ->first();
        if(!isset($product)){
            abort(404);
        }
        $product = $product->toArray();
        
        $this->product_id = $id = $product['id'];
        if(!empty($this->variant)){
            $default = ProductVariant::select('id as variant_id','images','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                    ->where('id',$this->variant)
                                    ->first()->toArray();
        }else{            
            $default = ProductVariant::select('id as variant_id','images','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                    ->whereIsDefault('yes')                                     
                                    ->whereProductId($product['id'])
                                    ->first()->toArray();
            $this->variant = $default['variant_id'];
            // $this->product_variant = encrypt($this->variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->product_variant = $this->variant;
        }

        $discount = $price = $sale_price = 0;
      
        $label = Label::find($product['label_id']);
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
                    $discount = ($sale_price/$price)*100;
                } 

            }else{
                $sale_price = $default['sale_price'];
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

        $order_item = OrderItem::whereHas('orders',function($q){
            $q->whereUserId(auth()->user()->id??0);
        })->whereProductId($product['id'])->first();

        $images = json_decode($default['images'], true);
        $this->images = (count($images)!=0)?$images:json_decode($product['images'], true);
        $this->stock_status = $default['stock_status'];
        $this->price = $price;
        $this->sale_price = $sale_price;
        $this->discount = ($discount!=0)?(100 - round($discount)):0;
        
        $product['label'] = (isset($label->name))?$label->name:'';
        $product['label_color_code'] = (isset($label->color))?$label->color:'';
        $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
        $product['review_count'] = $rating_count;
        $product['is_purchased'] = isset($order_item)?'yes':'no';
        $product['is_reviewed'] = isset($order_item->review)?'yes':'no';
        $attribute_id = array_values(array_filter(explode(',',$product['attribute_ids'])));   

        $this->parent_attribute_id = explode(',',array_shift($attribute_id));    
        $attribute_id = $attribute_id??[];

        $this->parent_attribute_set_id = ProductAttributeSet::whereProductVariantId($default['variant_id'])->whereAttributeId($this->parent_attribute_id)->pluck('attribute_set_id')->first();
        $this->parent_available_variant_ids = ProductAttributeSet::whereProductId($product['id'])->whereAttributeSetId($this->parent_attribute_set_id)->pluck('product_variant_id')->toArray();
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

        $selected_attributes_set_ids = ProductAttributeSet::whereProductVariantId($default['variant_id'])->pluck('attribute_set_id')->toArray();
        $this->selected_attributes_set_ids = array_fill_keys($selected_attributes_set_ids, true);
        $this->product = $product;
        
        // Related product // you might also like
        $related_product_ids = array_unique(array_merge($related_product_ids, array_filter(explode(',',$product['related_product_ids']))));
        $this->productList('related_products',json_encode($related_product_ids));

        
        // FREQUENTLY BOUGHT
        $frequently =  Product::leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                                ->select('products.id as id', \DB::raw('COUNT(order_items.product_id) as count'))
                                ->groupBy('id')
                                ->havingRaw('count != 0')
                                ->orderBy('count', 'desc')
                                ->limit(12)
                                ->pluck('id')->toArray();
                                
        $this->productList('frequently_bought_products',json_encode($frequently));

        $this->buying_options = BuyingOption::whereStatus('active')->get()->toArray();
   
        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;
    }

    public function productList($type,$product_ids)
    {        
        dd($product_ids);

        $ids = json_decode($product_ids);
        $Products = Product::select('id','slug','name','images','label_id','tax_ids','created_at')
                            ->whereIn('id',$ids)
                            ->get()
                            ->sortBy(function ($product) use ($ids) {
                                return array_search($product->id, $ids);
                            })->toArray();
        $this->$type = array_map(function ($product) 
        {

            $default = ProductVariant::select('id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration')
                                            ->whereIsDefault('yes')                                     
                                            ->whereProductId($product['id'])->first();
            $discount = $price = $sale_price = 0;
    
            $label = Label::find($product['label_id']);
            $rating_count = Review::whereProductId($product['id'])->count();
            $rating_sum = Review::whereProductId($product['id'])->sum('rating');

            if(isset($default))
            {
                $stock_status = ProductVariant::whereStockStatus('in_stock')->whereProductId($product['id'])->count();

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
            $product['stock_status'] = (!isset($stock_status))?'out_of_stock':'in_stock';
            $product['price'] = $price;
            $product['slug'] = $product['slug'];
            $product['variant_id'] = $default->id??0;
            $product['sale_price'] = $sale_price;
            $product['discount'] = ($discount!=0)?(100 - round($discount)):0;
            $product['label'] = (isset($label->name))?$label->name:'';
            $product['label_color_code'] = (isset($label->color))?$label->color:'';
            $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $product['review_count'] = $rating_count;
            $product['is_purchased'] = isset($order_item)?'yes':'no';
            $product['is_reviewed'] = isset($order_item->review)?'yes':'no';
            $product['product_type'] = ProductVariant::whereProductId($product['id'])->count();
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
    
    public function checkout()
    {
        return redirect()->to('checkout');
    }

    public function render()
    {
        return view('livewire.ecommerce.product.detail');
    }
}
