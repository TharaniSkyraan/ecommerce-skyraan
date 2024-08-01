<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Review;
use App\Models\SpecialProduct;
use App\Models\Collection;
use App\Models\Banner;
use App\Models\ProductStock;
use App\Models\WishList;
use App\Models\Label;
use Carbon\Carbon;
use App\Models\Tax;
use Auth;

class ProductList extends Component
{
    
    public $category,$availablestock,$rating,$min_price,$max_price,$sort_by,$type,$slug;
    public $view = 'two';
    public $initiate = true;
    public $pageloading = 'false';
    public $morepage = false;
    public $wishlist = [];
    public $page = 1;
    public $products = [];
    public $warehouse_ids = [];


    protected $queryString = ['category','availablestock','rating','min_price','max_price','sort_by'];

    protected $listeners = ['loadMore','GetFilters','InitiateFilters','GetView','GetSortBy'];

    public function loadMore()
    {
        $this->page++;
        $this->filterProduct();
    }
    public function GetFilters($filters)
    {   
        $this->ResetAllFilters();
        foreach($filters as $key =>$filter){
            $this->$key = (is_array($filter))?implode(',',$filter):$filter;
        }
        $this->filterProduct();
    }

    public function InitiateFilters($type='')
    {   
        if($this->initiate){
            
            $filters = [];
            if(!empty($this->min_price)){
                $filters['min_price'] = $this->min_price;
            }
            if(!empty($this->max_price)){
                $filters['max_price'] = $this->max_price;
            }
            $filters['category'] = explode(',',$this->category);
            $filters['availablestock'] = explode(',',$this->availablestock);
            $filters['rating'] = explode(',',$this->rating);
            
            if($this->sort_by!='all'){
                $this->emit('GetSortBySelf', $this->sort_by); 
            }
            $this->emit('PopFilters', $filters); 
            
            $this->initiate = false;
        }
    }

    public function GetView($view){
        $this->view = $view;
    }

    public function GetSortBy($sort_by){
        $this->ResetAllFilters();
        $this->sort_by = $sort_by;
        $this->filterProduct();
    }
    
    public function ResetAllFilters(){
        $this->reset(['page','category','rating','availablestock','min_price','max_price']);  
    }

    public function mount($type,$slug)
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));

        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;

        $this->type = $type;
        $this->slug = $slug;
        if(empty($this->category)){
            $this->filterProduct('initiate');
        }else{
            $this->filterProduct();
        }
    }

    public function filterProduct($init='')
    {
        $Products = Product::whereHas('product_stock', function($q){
                                $q->whereIn('warehouse_id', $this->warehouse_ids);
                            })->select('id','slug','label_id','category_ids','name','images','rating','stock_status','tax_ids','created_at')
                            ->whereStatus('active');
        
        if($this->type=='search'){            
            $Products->where('name', 'like', "%{$this->slug}%");
        }else
        if($this->type=='collection'){
            $product_ids = Collection::whereSlug($this->slug)->pluck('product_ids')->first();
        }else
        if($this->type=='special'){
            $product_ids = SpecialProduct::pluck('product_ids')->first();
        }else
        if($this->type=='product-collection'){
            $product_ids = Banner::whereSlug($this->slug)->pluck('product_ids')->first();
        }
        if(isset($product_ids)){
            $Products->whereIn('id',explode(',',$product_ids));
        }
        if (!empty($this->category) || $this->type=='category') { 
            $categories = !empty($this->category)?explode(',',$this->category):Category::whereSlug($this->slug)->pluck('id')->toArray();
            $Products->where(function($query) use ($categories) {
                foreach ($categories as $category) {
                    $query->orWhere('category_ids', 'like', '%,'.$category.',%');
                }
            });
        }  
        if (!empty($this->rating)) { 
            $Products->whereIn('rating', explode(',',$this->rating));
        }  
        if (!empty($this->availablestock)) { 
            $Products->whereIn('stock_status', explode(',',$this->availablestock));
        }  
        $min_price = ($this->min_price==0)?1:$this->min_price;
        $max_price = $this->max_price;

        if(!empty($min_price) && !empty($max_price))
        {                
            $Products->whereHas('product_variant',function($q) use($min_price,$max_price){
                $q->whereBetween('search_price', [$min_price, $max_price]);
            });
        }

        if(!empty($init)){
            $categories_ids = $Products->pluck('category_ids')->toArray();
            $categories_ids = array_map(function ($categories_id) {
                return explode(',', $categories_id);
            }, $categories_ids);

            $categories_ids = array_filter(array_unique(array_merge(...$categories_ids)));
            $this->category = implode(',',$categories_ids);
            $this->initiate = true;
        }

        if($this->sort_by != 'all' && !empty($this->sort_by)){
            $orderby = explode('-',$this->sort_by);

            if($orderby[0]=='title'){
                $orderby[0] = 'name';
            }
            if($orderby[1]=='descending'){
                $orderby[1] = 'DESC';
            }else{
                $orderby[1] = 'ASC';
            }
            
            if($orderby[0]=='price'){
                $Products->orderBy(
                    ProductVariant::whereHas('product_stock', function($q){
                        $q->whereIn('warehouse_id', $this->warehouse_ids);
                    })->select('search_price')
                    ->whereColumn('product_id', 'products.id')
                    ->whereIn('is_default', ['yes', 'no'])
                    ->orderByRaw("is_default = 'yes' DESC")
                    ->orderBy('search_price')
                    ->limit(1),  $orderby[1]
                );
            }else{
                $Products->orderBy($orderby[0],$orderby[1]);
            }
        }


        $Products = $Products->paginate(20, ['*'], 'page', $this->page);

        $this->morepage = $Products->hasMorePages();

        $products = array_map(function ($product) {

            $default = ProductVariant::whereHas('product_stock', function($q){
                                        $q->whereIn('warehouse_id', $this->warehouse_ids);
                                    })->select('id','price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
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
            $product['slug'] = $product['slug'];
            $product['price'] = $price;
            $product['slug'] = $product['slug'];
            $product['variant_id'] = $default->id??0;
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

        }, $Products->toArray()['data']);

        if($this->page!=1){
            $this->products = array_merge($this->products, $products);
        }else{
            $this->products = $products;
        }
        
        $this->pageloading = 'false';
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
        if($this->initiate){
            $this->emit('InitiateFilters');
        }
        return view('livewire.ecommerce.product.product-list');
    }

}
