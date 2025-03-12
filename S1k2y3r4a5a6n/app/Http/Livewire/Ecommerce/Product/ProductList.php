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
use App\Models\ProductSearches;
use App\Models\ProductStock;
use App\Models\WishList;
use App\Models\Label;
use Carbon\Carbon;
use App\Models\Tax;
use Auth;

class ProductList extends Component
{
    
    public $category,$availablestock,$rating,$min_price,$max_price,$product_max_price,$sort_by,$type,$slug;
    public $view = 'two';
    public $initiate = true;
    public $pageloading = 'false';
    public $morepage = false;
    public $wishlist = [];
    public $page = 1;
    public $products = [];
    public $warehouse_ids = [];

    protected $queryString = ['category','availablestock','rating','min_price','max_price','sort_by'];

    protected $listeners = ['loadMore','GetFilters','InitiateFilters','GetView','GetSortBy','addremoveWish'];

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
            $filters['product_max_price'] = ($this->product_max_price!=0)?$this->product_max_price:100;
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
        $zones = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zones['warehouse_ids']));

        $wishlist = WishList::whereUserId(\Auth::user()->id??0)->pluck('product_ids')->first();
        $wishlist = (isset($wishlist)?explode(',',$wishlist):[]);
        $this->wishlist = $wishlist;

        $this->type = $type;
        $this->slug = $slug;
        if(empty($this->category) && $this->type!='category'){
            $this->filterProduct('initiate');
        }else{
            $this->filterProduct();
        }
    }

    public function filterProduct($init='')
    {
        
        $warehouse_ids = (count($this->warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $this->warehouse_ids)) . ')(,|$)':'';

        $Products = ProductSearches::where('status','active')
                                    ->where(function($q) use($warehouse_ids){
                                        if((count($this->warehouse_ids)!=0)){
                                            $q->where('warehouse_ids', 'REGEXP', $warehouse_ids);
                                        }
                                    })->select('*','product_name as name','product_created_at as created_at','product_id as id');
              
        if($this->type=='search'){            
            $Products = $Products->where('product_name', 'like', "%{$this->slug}%");
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
            $Products = $Products->whereIn('product_id',explode(',',$product_ids));
        }
        if (!empty($this->availablestock)) { 
            $Products = $Products->whereHas('productvariant', function($q){
                $q->whereHas('product_stock', function($q1){
                    if((count($this->warehouse_ids)!=0)){
                        $q1->whereIn('warehouse_id', $this->warehouse_ids);
                    }
                    if (!empty($this->availablestock)) { 
                        $q1->whereIn('stock_status', explode(',',$this->availablestock));
                    }  
                });
            });
        }
        if (!empty($this->rating)) { 
            $Products = $Products->whereIn('review', explode(',',$this->rating));
        }  

        if (!empty($this->category) || $this->type=='category') { 

            $categories = !empty($this->category)?explode(',',$this->category):Category::whereSlug($this->slug)->pluck('id')->toArray();
            $category_ids = (count($categories)!=0)?'(^|,)(' . implode('|', array_map('intval', $categories)) . ')(,|$)':'';
            $this->category = implode(',',$categories);
            $Products = $Products->where('category_ids', 'REGEXP', $category_ids);
            $maxPrice = $Products;
        }else{
            if(!empty($init)){

                $prdCategory = $maxPrice = $Products;                
                $categories_ids = $prdCategory->pluck('category_ids')->toArray();
                $categories_ids = array_map(function ($categories_id) {
                    return explode(',', $categories_id);
                }, $categories_ids);

                $categories_ids = array_filter(array_unique(array_merge(...$categories_ids)));
                $this->category = implode(',',$categories_ids);
            }
        }  
        if(empty($this->product_max_price) && isset($maxPrice)){
            $this->product_max_price = $maxPrice->orderBy('search_price','desc')->pluck('search_price')->first();
        }
        
        $min_price = ($this->min_price==0)?1:$this->min_price;
        $max_price = $this->max_price;

        if(!empty($min_price) && !empty($max_price))
        {                
            $Products = $Products->whereBetween('search_price', [$min_price, $max_price]);
        }

        if($this->sort_by != 'all' && !empty($this->sort_by)){
            $orderby = explode('-',$this->sort_by);

            if($orderby[0]=='title'){
                $orderby[0] = 'product_name';
            } 
            if($orderby[0]=='created_at'){
                $orderby[0] = 'updated_at';
            }
            if($orderby[0]=='price'){
                $orderby[0] = 'search_price';
            }
            if($orderby[1]=='descending'){
                $orderby[1] = 'DESC';
            }else{
                $orderby[1] = 'ASC';
            }
            
            $Products = $Products->orderBy($orderby[0],$orderby[1]);
        }


        $Products = $Products->paginate(20, ['*'], 'page', $this->page);
        $this->emit('TotalRecord',$Products->total());
        
        $this->morepage = $Products->hasMorePages();

        $products = array_map(function ($product) {

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
