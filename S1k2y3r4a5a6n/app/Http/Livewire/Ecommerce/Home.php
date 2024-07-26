<?php

namespace App\Http\Livewire\Ecommerce;

use Livewire\Component;
use App\Models\Banner;
use App\Models\Tax;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Label;
use App\Models\WishList;
use App\Models\ProductVariant;
use App\Models\WhyChoose;
use App\Models\ProductStock;
use App\Models\BuyingOption;
use Auth;
use Carbon\Carbon;

class Home extends Component
{

    public $wishlist = [];
    public $warehouse_ids = [];


    public function mount()
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
        $banners = Banner::wherePromotionBanner('no')
                                ->whereSpecialProduct('no')
                                ->whereStatus('active')
                                ->orderBy('created_at','desc')
                                ->limit(10)
                                ->get()->each(function ($items) {
                                    $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                          ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                          ->pluck('id')->first();

                                    $items->append(['product_slug','product_created']);
                                })->toArray();
        $this->banners = array_filter($banners, function($banner) {
                                return $banner['product_stock'] !== null;
                            });

        $promotion_banners = Banner::wherePromotionBanner('yes')
                                        ->whereStatus('active')
                                        ->orderBy('created_at','desc')
                                        ->limit(2)
                                        ->get()->each(function ($items) {
                                            $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                                  ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                                  ->pluck('id')->first();
                                            $items->append(['product_slug','product_created']);
                                        })->toArray();
        $this->promotion_banners = array_filter($promotion_banners, function($banner) {
                                        return $banner['product_stock'] !== null;
                                    });

        $special_products = Banner::whereSpecialProduct('yes')
                                        ->whereStatus('active')
                                        ->orderBy('created_at','desc')
                                        ->get()->each(function ($items) {
                                            $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                                  ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                                  ->pluck('id')->first();
                                            $items->append(['product_slug','product_created']);
                                        })->toArray();
        $this->special_products = array_filter($special_products, function($banner) {
                                        return $banner['product_stock'] !== null;
                                    });

        $categories = Category::whereNull('parent_id')
                                    ->whereStatus('active')
                                    ->orderBy('sort','asc')// ->limit(10)
                                    ->get()->each(function ($items) {
                                        $items['product'] = Product::whereHas('product_stock', function($q){
                                                                        $q->whereIn('warehouse_id', $this->warehouse_ids);
                                                                    })->where('category_ids', 'like', '%,'.$items->id.',%')
                                                                      ->pluck('id')->first();

                                    })->toArray();
        $this->categories = array_filter($categories, function($category) {
                                        return $category['product'] !== null;
                                    });
                                    
        $new_product_ids = ProductVariant::whereHas('product', function($q){
                                                $q->where('status','active');
                                            })->whereHas('product_stock', function($q1){
                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                            })
                                            ->orderBy('created_at','desc')
                                            ->limit(4)
                                            ->pluck('id')->toArray();
        $this->productList('new_products',json_encode($new_product_ids));

        $top_selling_product_ids = Product::whereHas('product_stock', function($q1){
                                                $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                          })->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                                          ->select('products.id as id', \DB::raw('COUNT(order_items.product_id) as count'))
                                          ->groupBy('id')
                                          ->havingRaw('count != 0')
                                          ->orderBy('count', 'desc')
                                          ->limit(12)
                                          ->pluck('id')->toArray();
        
        $this->productList('top_selling_products',json_encode($top_selling_product_ids));

        $startDate = Carbon::now()->subWeek()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
                                            
        $hero_of_the_week_ids = Product::whereHas('product_stock', function($q1){
                                            $q1->whereIn('warehouse_id', $this->warehouse_ids);
                                        })->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                                       ->whereBetween('order_items.created_at', [$startDate, $endDate])
                                       ->select('products.id as id', \DB::raw('COUNT(order_items.product_id) as count'),'products.name as name')
                                       ->groupBy('id')
                                       ->groupBy('name')
                                       ->havingRaw('count != 0')
                                       ->orderBy('count', 'desc')
                                       ->limit(15)
                                       ->pluck('id')->toArray();

        $this->productList('hero_of_the_week',json_encode($hero_of_the_week_ids));

        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;
        $why_choose = WhyChoose::all();
        $this->why_choose = $why_choose;
        $reviews = Review::where('rating', 5)->with('user')->get();
        $this->reviews = $reviews;

        $collections = BuyingOption::where('status', 'active')
                                ->where(function ($query) {
                                    $query->where('feature_type', '!=', 'buying');
                                })
                                ->get()
                                ->toArray();

        $count = count($collections);
        $duplicationCount = 10 - $count;
        
        $data = $collections;
        for ($i = 0; $i < $duplicationCount; $i++) {
            $data = array_merge($data, $collections);
        }
        
        // Split the array into chunks
        $result = array_chunk($data, 5);
        
        // Ensure the result is not empty before proceeding
        if (!empty($result)) {
            // Check the count of the last chunk
            $lastChunkIndex = count($result) - 1;
            $lastChunkCount = count($result[$lastChunkIndex]);
        
            // If the last chunk has fewer than 5 elements, merge it with the first chunk
            if ($lastChunkCount < 5) {
                if (isset($result[0])) {
                    $result[$lastChunkIndex] = array_slice(array_merge($result[0], $result[$lastChunkIndex]), 0, 5);
                    unset($result[0]); // Unset the first chunk after merging
                }
            }
        
            // Ensure collections are not empty before assigning
            if (!empty($result)) {
                $this->collections = $result[0];
                unset($result[0]);
            } else {
                $this->collections = [];
            }
            
            $this->collections_data = $result;
        } else {
            // Handle the case where result is empty
            $this->collections = [];
            $this->collections_data = [];
        }
        
    }
    public function productList($type,$ids){
        
        if($type !='new_products')
        {

            $ids=json_decode($ids);
            $Products = Product::select('id','slug','name','images','label_id','tax_ids','created_at')
                                ->whereIn('id',$ids)
                                ->get()
                                ->sortBy(function ($product) use ($ids) {
                                    return array_search($product->id, $ids);
                                })->toArray();
            $this->$type = array_map(function ($product) 
            {

                $default = ProductVariant::whereHas('product_stock', function($q){
                                                $q->whereIn('warehouse_id', $this->warehouse_ids);
                                        })->select('id','price','sale_price','is_default','cart_limit','discount_expired','discount_start_date','discount_end_date','discount_duration')
                                        // ->whereIsDefault('yes')    
                                        ->where(function($q){
                                            $q->whereIn('is_default', ['yes', 'no']);
                                        })
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
                $product['image1'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                $product['image2'] = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
                $product['price'] = $price;
                $product['slug'] = $product['slug'];
                $product['variant_id'] = $default->id??0;
                $product['cart_limit'] = $default->cart_limit??0;
                $product['sale_price'] = $sale_price;
                $product['discount'] = ($discount!=0)?(round($discount)):0;
                $product['label'] = (isset($label->name))?$label->name:'';
                $product['label_color_code'] = (isset($label->color))?$label->color:'';
                $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
                $product['review_count'] = $rating_count;
                $product['product_type'] = ProductVariant::whereProductId($product['id'])->count();
                $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
                $product['available_quantity'] = $product_stock->available_quantity??0;
                $product['product_stock_id'] = $product_stock->id??0;
                return $product;

            }, $Products);

        }else{
            
            $ids=json_decode($ids);

            $Products = ProductVariant::whereHas('product_stock', function($q){
                                                $q->whereIn('warehouse_id', $this->warehouse_ids);
                                        })->select('id as variant_id','product_id as id','price','sale_price','cart_limit','discount_expired','discount_start_date','discount_end_date','discount_duration')
                                        ->whereIn('id',$ids)  
                                        ->get()
                                        ->sortBy(function ($product) use ($ids) {
                                            return array_search($product->variant_id, $ids);
                                        })->toArray();
                                            
            $this->$type = array_map(function ($default) 
            {
                
                $product_stock = ProductStock::select('id', 'available_quantity')
                                            ->whereIn('warehouse_id',$this->warehouse_ids)
                                            ->whereProductVariantId($default['variant_id'])
                                            ->groupBy('id', 'available_quantity')
                                            ->orderBy('available_quantity','desc')
                                            ->first();

                $product = Product::select('id','slug','name','images','label_id','tax_ids','created_at')->where('id',$default['id'])->first()->toArray();

                $discount = $price = $sale_price = 0;
              
                $label = Label::where('id',$product['label_id'])->whereStatus('active')->first();
                $rating_count = Review::whereProductId($product['id'])->count();
                $rating_sum = Review::whereProductId($product['id'])->sum('rating');

                $price = $default['price'];
                
                if($default['sale_price']!=0 && $default['discount_expired'] != 'yes'){
                    
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

                $images = json_decode($product['images'], true);
                $product['image1'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                $product['image2'] = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
                $product['slug'] = $product['slug'];
                $product['price'] = $price;
                $product['variant_id'] = $default['variant_id'];
                $product['cart_limit'] = $default['cart_limit'];
                $product['sale_price'] = $sale_price;
                $product['discount'] = ($discount!=0)?(round($discount)):0;
                $product['label'] = (isset($label->name))?$label->name:'';
                $product['label_color_code'] = (isset($label->color))?$label->color:'';
                $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
                $product['review_count'] = $rating_count;
                $product['product_type'] = 1;
                $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
                $product['available_quantity'] = $product_stock->available_quantity??0;
                $product['product_stock_id'] = $product_stock->id??0;
                return $product;

            }, $Products);
        }

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

    public function render()
    {
        return view('livewire.ecommerce.home');
    }
}
