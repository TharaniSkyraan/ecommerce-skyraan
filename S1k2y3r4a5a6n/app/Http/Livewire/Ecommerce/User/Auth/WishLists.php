<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use App\Models\WishList;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Label;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Tax;
use Carbon\Carbon;

class WishLists extends Component
{
    public $wishlist,$products;
    public $warehouse_ids = [];
    
    protected $listeners = ['addremoveWish'];
    
    public function addremoveWish($product_id)
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));
        
        if(\Auth::check()){
            $wishlistadded = Wishlist::whereUserId(auth()->user()->id)->where('product_ids', 'REGEXP', $product_id)->first();
           
            if(!isset($wishlistadded))
            { 
                $product_ids = array_filter(array_values($this->wishlist));
                array_push($product_ids, $product_id);
                $this->wishlist = $product_ids;
                $wishlist['product_ids'] = ','.implode(',', $product_ids).',';
                $wishlist['user_id'] = auth()->user()->id;

                Wishlist::updateOrCreate(
                    ['user_id' => auth()->user()->id],
                    $wishlist
                );
            }else
            {
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
        
        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;

        
        $Products = Product::select('id','slug','name','images','label_id','tax_ids','created_at')
                            ->whereIn('id',$wishlist)
                            ->get()
                            ->sortBy(function ($product) use ($wishlist) {
                                return array_search($product->id, $wishlist);
                            })->toArray();
        $this->products = array_map(function ($product) 
        {

            $default = ProductVariant::select('id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration')
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
            $product['sale_price'] = $sale_price;
            $product['discount'] = ($discount!=0)?(round($discount)):0;
            $product['label'] = (isset($label->image))?asset('storage').'/'.$label->image:'';
            $product['review'] = ($rating_count!=0)?round($rating_sum/$rating_count):0;
            $product['review_count'] = $rating_count;
            $product['product_type'] = ProductVariant::whereProductId($product['id'])->count();
            $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
                
            return $product;

        }, $Products);
        
        return view('livewire.ecommerce.user.auth.wish-lists');
    }
}
