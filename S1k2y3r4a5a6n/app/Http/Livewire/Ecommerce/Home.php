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
use App\Models\ProductSearches;
use App\Models\WhyChoose;
use App\Models\ProductStock;
use App\Models\BuyingOption;
use Auth;
use Carbon\Carbon;

class Home extends Component
{

    public $wishlist = [];
    public $warehouse_ids = [];

    protected $listeners = ['addremoveWish'];

    public function mount()
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
        $warehouse_ids = (count($this->warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $this->warehouse_ids)) . ')(,|$)':'';

        $banners = Banner::wherePromotionBanner('no')
                                ->whereSpecialProduct('no')
                                ->whereStatus('active')
                                ->orderBy('sort','asc')
                                ->limit(10)
                                ->get()->each(function ($items) {
                                    $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                          ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                          ->pluck('id')->first();

                                    if($items->product_type=='single'){
                                        $product_variant = ProductSearches::whereIn('product_id',array_values(array_filter(explode(',',$items->product_ids))))
                                                                        ->where('status','active')
                                                                        ->first();
                                    }
                                    $items['product_slug'] = $product_variant->slug??'';
                                    $items['variant_id'] = $product_variant->variant_id??'';
                                    $items['product_created'] = $product_variant->product_created_at??'';
                                    // $items->append(['product_slug','product_created']);
                                    return $items;
                                })->toArray();
        $this->banners = (count($this->warehouse_ids)!=0)?array_filter($banners, function($banner) {return $banner['product_stock'] !== null; }):$banners;
        $promotion_banners = Banner::wherePromotionBanner('yes')
                                        ->whereStatus('active')
                                        ->orderBy('created_at','desc')
                                        ->limit(2)
                                        ->get()->each(function ($items) {
                                            $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                                  ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                                  ->pluck('id')->first();
                                            if($items->product_type=='single'){
                                                $product_variant = ProductSearches::whereIn('product_id',array_values(array_filter(explode(',',$items->product_ids))))
                                                                                ->where('status','active')
                                                                                ->first();
                                            }
                                            $items['product_slug'] = $product_variant->slug??'';
                                            $items['variant_id'] = $product_variant->variant_id??'';
                                            $items['product_created'] = $product_variant->product_created_at??'';
                                            return $items;

                                        })->toArray();
        $this->promotion_banners = (count($this->warehouse_ids)!=0)?array_filter($promotion_banners, function($banner) {return $banner['product_stock'] !== null; }):$promotion_banners;

        $special_products = Banner::whereSpecialProduct('yes')
                                        ->whereStatus('active')
                                        ->orderBy('created_at','desc')
                                        ->take(2)
                                        ->get()->each(function ($items) {
                                            $items['product_stock'] = ProductStock::whereProductId(array_values(array_filter(explode(',',$items->product_ids))))
                                                                                  ->whereIn('warehouse_id', $this->warehouse_ids)
                                                                                  ->pluck('id')->first();  
                                            if($items->product_type=='single'){
                                                $product_variant = ProductSearches::whereIn('product_id',array_values(array_filter(explode(',',$items->product_ids))))
                                                                                ->where('status','active')
                                                                                ->first();
                                            }
                                            $items['product_slug'] = $product_variant->slug??'';
                                            $items['variant_id'] = $product_variant->variant_id??'';
                                            $items['product_created'] = $product_variant->product_created_at??'';
                                            return $items;
                                        })->toArray();
        $this->special_products = (count($this->warehouse_ids)!=0)?array_filter($special_products, function($banner) {return $banner['product_stock'] !== null; }):$special_products;
       
        $categories = Category::whereNull('parent_id')
                                    ->whereStatus('active')
                                    ->orderBy('sort','asc')// ->limit(10)
                                    ->get()->each(function ($items) use($warehouse_ids) {                                        
                                        $items['product'] = ProductSearches::where('warehouse_ids', 'REGEXP', $warehouse_ids)
                                                                            ->where('category_ids', 'like', '%,'.$items->id.',%')
                                                                            ->where('status','active')
                                                                            ->pluck('id')->first();
                                    })->toArray();
        $this->categories = (count($this->warehouse_ids)!=0)?array_filter($categories, function($category) {
                                        return $category['product'] !== null;
                                    }):$categories;
                                    
        
        $this->productList('new_products',$warehouse_ids);
        $this->productList('top_selling_products',$warehouse_ids);
        $this->productList('hero_of_the_week',$warehouse_ids);

        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;
        $why_choose = WhyChoose::all();
        $this->why_choose = $why_choose;
        $reviews = Review::where('rating', 5)->with('user')->get();
        $this->reviews = $reviews;

        $collections = BuyingOption::where('status', 'active')
                                ->where('feature_type', '!=', 'buying')
                                ->get()
                                ->toArray();  

        // Check if collections are less than 5, if so, duplicate the items to make a continuous loop
        if (count($collections) < 5) {
            $collections = array_merge($collections, $collections, $collections); // Duplicate to fill space
        } elseif (count($collections) == 1) {
            $collections = array_fill(1, 6, $collections[1]); // Duplicate single image to fill space
        }

        $this->collections = $collections;  
        
    }
    public function productList($type,$warehouse_ids)
    {
        
        if($type=='new_products'){
            
            $Products = ProductSearches::where('status','active')
                                ->where(function($q) use($warehouse_ids){
                                    if((count($this->warehouse_ids)!=0)){
                                        $q->where('warehouse_ids', 'REGEXP', $warehouse_ids);
                                    }
                                })->orderBy('created_at', 'desc')
                                ->limit(8)
                                ->get()
                                ->toArray();
        }elseif($type=='top_selling_products')
        {
            $ids = ProductSearches::where('status','active')
                                        ->where(function($q) use($warehouse_ids){
                                            if((count($this->warehouse_ids)!=0)){
                                                $q->where('warehouse_ids', 'REGEXP', $warehouse_ids);
                                            }
                                        })->leftJoin('order_items', 'order_items.product_id', '=', 'product_searches.product_id')
                                        ->select('product_searches.id as id', \DB::raw('COUNT(order_items.product_id) as count'))
                                        ->groupBy('id')
                                        ->havingRaw('count != 0')
                                        ->orderBy('count', 'desc')
                                        ->limit(12)
                                        ->pluck('id')->toArray();
        }elseif($type=="hero_of_the_week"){
            
            $startDate = Carbon::now()->subWeek()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
                                                
            $ids = ProductSearches::where('status','active')
                                        ->where(function($q) use($warehouse_ids){
                                            if((count($this->warehouse_ids)!=0)){
                                                $q->where('warehouse_ids', 'REGEXP', $warehouse_ids);
                                            }
                                        })->leftJoin('order_items', 'order_items.product_id', '=', 'product_searches.product_id')
                                        ->whereBetween('order_items.created_at', [$startDate, $endDate])
                                        ->select('product_searches.id as id', \DB::raw('COUNT(order_items.product_id) as count'))
                                        ->groupBy('id')
                                        ->havingRaw('count != 0')
                                        ->orderBy('count', 'desc')
                                        ->limit(15)
                                        ->pluck('id')->toArray();
        }

        if($type !='new_products'){
            $Products = ProductSearches::whereIn('id',$ids)
            ->get()
            ->sortBy(function ($product) use ($ids) {
                return array_search($product->id, $ids);
            })->toArray();
        }
        $this->$type = array_map(function ($product) 
        {

            $discount = $price = $sale_price = 0;

            $rating_count = Review::whereProductId($product['id'])->count();
            $rating_sum = Review::whereProductId($product['id'])->sum('rating');

            $product_stock = ProductStock::select('id', 'available_quantity')
                                        ->whereIn('warehouse_id',$this->warehouse_ids)
                                        ->whereProductVariantId($product['variant_id'])
                                        ->groupBy('id', 'available_quantity')
                                        ->orderBy('available_quantity','desc')
                                        ->first();

            $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $product['review_count'] = $rating_count;
            $product['product_type'] = ProductVariant::whereProductId($product['id'])->count();
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

    public function render()
    {
        return view('livewire.ecommerce.home');
    }
}
