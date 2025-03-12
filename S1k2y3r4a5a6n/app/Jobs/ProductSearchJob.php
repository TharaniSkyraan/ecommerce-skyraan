<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProductSearches;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use App\Models\ProductAttributeSet;
use App\Models\Zone;
use App\Models\Label;
use App\Models\Tax;
use App\Models\Category;
use App\Models\Review;
use Carbon\Carbon;

class ProductSearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $type,$id;

    public function __construct($data)
    {
        //
        $this->type = $data['type'];
        $this->id = $data['id'];
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        if($this->type =='product_update'){

            if(ProductStock::whereProductVariantId($this->id)->exists()){
            
                $productVariant = ProductVariant::find($this->id);
                $product_id = $productVariant->product_id;

                $prdSearch = ProductSearches::whereVariantId($this->id)->whereProductId($product_id)->first();
    
                $product = Product::find($product_id);
                $label = Label::where('id',$product->label_id)->whereStatus('active')->first();
    
                // rating
                $rating_count = Review::whereProductId($product_id)->count();
                $rating_sum = Review::whereProductId($product_id)->sum('rating');
    
                $zone_warehouse_ids = Zone::whereStatus('active')->pluck('warehouse_ids')->toArray();
                $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));
    
                // Optionally, remove duplicates
                $zone_warehouse_ids = array_unique($flattenedArray);
                $warehouse_ids = ProductStock::whereHas('warehouse', function($q){
                    $q->whereStatus('active');
                })->whereProductVariantId($this->id)
                  ->whereIn('warehouse_id',$zone_warehouse_ids)
                  ->pluck('warehouse_id')->toArray();
                
                $attribute_set_ids = ProductAttributeSet::whereProductVariantId($this->id)->pluck('attribute_set_id')->toArray();
    
                if(isset($prdSearch)){
                    $searchPrd = ProductSearches::find($prdSearch->id);
                }else{
                    $searchPrd = new ProductSearches();
                }
                $searchPrd->status = (count($warehouse_ids)==0)?'inactive':(($product->status=='active')?'active':'inactive');
                $price = $productVariant->price;
    
                if($productVariant->sale_price!=0 && $productVariant->discount_expired!='yes')
                {
                    
                    if($productVariant->discount_duration=='yes'){
    
                        $currentDate = Carbon::now()->format('d-m-Y H:i');
    
                        // Start and end date from user input or database
                        $startDate = Carbon::parse($productVariant->discount_start_date)->format('d-m-Y H:i'); 
                        $endDate = Carbon::parse($productVariant->discount_end_date)->format('d-m-Y H:i'); 
    
                        // Validate start date
                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                            $sale_price = $productVariant->sale_price;
                            $discount = (($price-$sale_price)/$price)*100;
                        } 
    
                    }else{
                        $sale_price = $productVariant->sale_price;
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
                $product_price = (!empty($sale_price) && ($sale_price>0))?$sale_price:$price;
    
                $images = json_decode($productVariant->images, true);
                $images = (count($images)!=0)?$images:json_decode($product->images, true);
    
                $category_ids = Category::whereIn('id',explode(',',$product->category_ids))->whereStatus('active')->pluck('id')->toArray();
                $searchPrd->product_id = $productVariant->product_id;
                $searchPrd->slug = $product->slug;
                $searchPrd->variant_id = $productVariant->id;
                $searchPrd->images = json_encode($images, true);
                $searchPrd->image1 = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                $searchPrd->image2 = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
                $searchPrd->product_name = $productVariant->product_name;
                $searchPrd->price = $price;
                $searchPrd->sale_price = $sale_price;
                $searchPrd->search_price = $product_price;
                $searchPrd->discount = ($discount!=0)?(round($discount)):0;
                $searchPrd->discount_start_date = $productVariant->discount_start_date;
                $searchPrd->discount_end_date = $productVariant->discount_end_date;
                $searchPrd->cart_limit = $productVariant->cart_limit;
                $searchPrd->description = $product->description;
                $searchPrd->content = $product->content;
                $searchPrd->product_created_at = $product->created_at;
                $searchPrd->shipping_wide = $productVariant->shipping_wide;
                $searchPrd->shipping_length = $productVariant->shipping_length;
                $searchPrd->shipping_height = $productVariant->shipping_height;
                $searchPrd->shipping_weight = $productVariant->shipping_weight;
                $searchPrd->label = (isset($label->name))?$label->name:'';
                $searchPrd->label_color_code = (isset($label->color))?$label->color:'';
                $searchPrd->product_type = ProductVariant::whereProductId($product->id)->count();
                $searchPrd->category_ids = ','.implode(',',$category_ids).',';
                $searchPrd->warehouse_ids = ','.implode(',',$warehouse_ids).',';
                $searchPrd->attribute_set_ids = implode(',',$attribute_set_ids);
                $searchPrd->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
                $searchPrd->review_count = $rating_count;
                $searchPrd->save();
            }
    
        }elseif($this->type=='category_update'){
            
            $category = Category::find($this->id);

            if($category->status=='inactive' || !isset($category)){
                $searchPrds = ProductSearches::where('category_ids', 'like', '%,'.$this->id.',%')->select('id','status','category_ids')->get();
                foreach($searchPrds as $searchPrd){
                    $category_ids = array_filter(explode(',',$searchPrd->category_ids));
                    $category_ids = array_values(array_diff($category_ids, [$this->id]));
                    ProductSearches::where('id',$searchPrd->id)->update(['category_ids' => ','.implode(',',$category_ids).',',    
                    'status' => (count($category_ids)==0)?'inactive':(($products->status=='active')?'active':'inactive')
                   ]);
                }
            }else{
                
                $products = Product::where('category_ids', 'like', '%,'.$this->id.',%')->select('id','status','category_ids')->get();
                foreach($products as $product){
                    $category_ids = Category::whereIn('id',explode(',',$product->category_ids))->whereStatus('active')->pluck('id')->toArray();
                    ProductSearches::where('product_id',$product->id)->update(['category_ids' => ','.implode(',',$category_ids).',',    
                                                                               'status' => (count($category_ids)==0)?'inactive':(($products->status=='active')?'active':'inactive')
                                                                              ]);
                }
            }
        }elseif($this->type=='label_update'){
            $label = Label::find($this->id);
            $product_ids = Product::where('label_id', $this->id)->pluck('id')->toArray();
            if($label->status=='inactive' || !isset($label)){
                ProductSearches::whereIn('product_id',$product_ids)->update(['label' => '','label_color_code' => '']);
            }else{
                ProductSearches::whereIn('product_id',$product_ids)->update(['label' => (isset($label->name))?$label->name:'','label_color_code' => (isset($label->color))?$label->color:'']);
            }
        }elseif($this->type=='zone_update'){
            $data = $this->id;
            $zone = Zone::find($data['id']);
           
            $new_warehouse_ids  = $data['warehouse_ids'];
            $prev_warehouse_ids = $data['prev_warehouse_ids'];
            $status = $data['status']??'active';
            $prev_status = $data['prev_status']??'active';
    
            // Status update  
            if($status!=$prev_status){
    
                if($status=='active')
                {
                    $warehouse_ids = $new_warehouse_ids;
                    $productVariants = ProductStock::whereHas('warehouse', function($q){
                                                        $q->whereStatus('active');
                                                    })->whereIn('warehouse_id',$warehouse_ids)
                                                    ->pluck('product_variant_id')
                                                    ->toArray();
                    
                    foreach($productVariants as $variant_id)
                    {     
                        
                        if(ProductStock::whereProductVariantId($variant_id)->exists())
                        {
                        
                            $productVariant = ProductVariant::find($variant_id);

                            if(isset($productVariant))
                            {
                                $product_id = $productVariant->product_id;
    
                                $prdSearch = ProductSearches::whereVariantId($variant_id)->whereProductId($product_id)->first();
                    
                                $product = Product::find($product_id);
                                $label = Label::where('id',$product->label_id)->whereStatus('active')->first();
                    
                                // rating
                                $rating_count = Review::whereProductId($product_id)->count();
                                $rating_sum = Review::whereProductId($product_id)->sum('rating');
                    
                                $zone_warehouse_ids = Zone::whereStatus('active')->pluck('warehouse_ids')->toArray();
                                $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));
                    
                                // Optionally, remove duplicates
                                $zone_warehouse_ids = array_unique($flattenedArray);
                                $warehouse_ids = ProductStock::whereHas('warehouse', function($q){
                                    $q->whereStatus('active');
                                })->whereProductVariantId($variant_id)
                                ->whereIn('warehouse_id',$zone_warehouse_ids)
                                ->pluck('warehouse_id')->toArray();
                                
                                $attribute_set_ids = ProductAttributeSet::whereProductVariantId($variant_id)->pluck('attribute_set_id')->toArray();
                    
                                if(isset($prdSearch)){
                                    $searchPrd = ProductSearches::find($prdSearch->id);
                                }else{
                                    $searchPrd = new ProductSearches();
                                }
                                $searchPrd->status = (count($warehouse_ids)==0)?'inactive':(($product->status=='active')?'active':'inactive');
                                $price = $productVariant->price;
                    
                                if($productVariant->sale_price!=0 && $productVariant->discount_expired!='yes')
                                {
                                    
                                    if($productVariant->discount_duration=='yes'){
                    
                                        $currentDate = Carbon::now()->format('d-m-Y H:i');
                    
                                        // Start and end date from user input or database
                                        $startDate = Carbon::parse($productVariant->discount_start_date)->format('d-m-Y H:i'); 
                                        $endDate = Carbon::parse($productVariant->discount_end_date)->format('d-m-Y H:i'); 
                    
                                        // Validate start date
                                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                                            $sale_price = $productVariant->sale_price;
                                            $discount = (($price-$sale_price)/$price)*100;
                                        } 
                    
                                    }else{
                                        $sale_price = $productVariant->sale_price;
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
                                $product_price = (!empty($sale_price) && ($sale_price>0))?$sale_price:$price;
                    
                                $images = json_decode($product->images, true);
                                $images = (count($images)!=0)?$images:json_decode($productVariant->images, true);
                    
                                $category_ids = Category::whereIn('id',explode(',',$product->category_ids))->whereStatus('active')->pluck('id')->toArray();
                                $searchPrd->product_id = $productVariant->product_id;
                                $searchPrd->slug = $product->slug;
                                $searchPrd->variant_id = $productVariant->id;
                                $searchPrd->images = json_encode($images, true);
                                $searchPrd->image1 = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                                $searchPrd->image2 = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
                                $searchPrd->product_name = $productVariant->product_name;
                                $searchPrd->price = $price;
                                $searchPrd->sale_price = $sale_price;
                                $searchPrd->search_price = $product_price;
                                $searchPrd->discount = ($discount!=0)?(round($discount)):0;
                                $searchPrd->discount_start_date = $productVariant->discount_start_date;
                                $searchPrd->discount_end_date = $productVariant->discount_end_date;
                                $searchPrd->cart_limit = $productVariant->cart_limit;
                                $searchPrd->description = $product->description;
                                $searchPrd->content = $product->content;
                                $searchPrd->product_created_at = $product->created_at;
                                $searchPrd->shipping_wide = $productVariant->shipping_wide;
                                $searchPrd->shipping_length = $productVariant->shipping_length;
                                $searchPrd->shipping_height = $productVariant->shipping_height;
                                $searchPrd->shipping_weight = $productVariant->shipping_weight;
                                $searchPrd->label = (isset($label->name))?$label->name:'';
                                $searchPrd->label_color_code = (isset($label->color))?$label->color:'';
                                $searchPrd->product_type = ProductVariant::whereProductId($product->id)->count();
                                $searchPrd->category_ids = ','.implode(',',$category_ids).',';
                                $searchPrd->warehouse_ids = ','.implode(',',$warehouse_ids).',';
                                $searchPrd->attribute_set_ids = implode(',',$attribute_set_ids);
                                $searchPrd->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
                                $searchPrd->review_count = $rating_count;
                                $searchPrd->save();
                            
                            }
                        }    
                    }
                }
                if($status=='inactive')
                {
    
                    $warehouse_ids = (count($prev_warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $prev_warehouse_ids)) . ')(,|$)':'';
                    
                    $productVariants = ProductSearches::where('warehouse_ids', 'REGEXP', $warehouse_ids)->select('id','variant_id')->get();
    
                    foreach($productVariants as $variant)
                    {            
                        $zone_warehouse_ids = Zone::whereStatus('active')->pluck('warehouse_ids')->toArray();
                        $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));

                        // Optionally, remove duplicates
                        $zone_warehouse_ids = array_unique($flattenedArray);
                        $warehouse_ids = ProductStock::whereHas('warehouse', function($q){
                            $q->whereStatus('active');
                        })->whereProductVariantId($variant->variant_id)
                          ->whereIn('warehouse_id',$zone_warehouse_ids)
                          ->pluck('warehouse_id')->toArray();
            
                        $productVariant = ProductVariant::where('id',$variant->variant_id)->first();
                        ProductSearches::where('id',$variant->id)->update([
                            'warehouse_ids' => ','.implode(',',$warehouse_ids).',',
                            'status' => (count($warehouse_ids)==0)?'inactive':(($productVariant->product->status=='active')?'active':'inactive')
                        ]);             
                    }
    
                }
    
            }else{
    
                if($status=='active')
                {
                    // Check new warehouse added
                    
                    $warehouse_ids = array_values(array_diff($new_warehouse_ids, $prev_warehouse_ids));
    
                    $productVariants = ProductStock::whereHas('warehouse', function($q){
                                                        $q->whereStatus('active');
                                                    })->whereIn('warehouse_id',$warehouse_ids)
                                                    ->pluck('product_variant_id')
                                                    ->toArray();
                    
                    foreach($productVariants as $variant_id)
                    {     
                        
                        if(ProductStock::whereProductVariantId($variant_id)->exists()){
                        
                            $productVariant = ProductVariant::find($variant_id);

                            if(isset($productVariant))
                            {
                                $product_id = $productVariant->product_id;
    
                                $prdSearch = ProductSearches::whereVariantId($variant_id)->whereProductId($product_id)->first();
                    
                                $product = Product::find($product_id);
                                $label = Label::where('id',$product->label_id)->whereStatus('active')->first();
                    
                                // rating
                                $rating_count = Review::whereProductId($product_id)->count();
                                $rating_sum = Review::whereProductId($product_id)->sum('rating');
                    
                                $zone_warehouse_ids = Zone::whereStatus('active')->pluck('warehouse_ids')->toArray();
                                $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));
                    
                                // Optionally, remove duplicates
                                $zone_warehouse_ids = array_unique($flattenedArray);
                                $warehouse_ids = ProductStock::whereHas('warehouse', function($q){
                                    $q->whereStatus('active');
                                })->whereProductVariantId($variant_id)
                                ->whereIn('warehouse_id',$zone_warehouse_ids)
                                ->pluck('warehouse_id')->toArray();
                                
                                $attribute_set_ids = ProductAttributeSet::whereProductVariantId($variant_id)->pluck('attribute_set_id')->toArray();
                    
                                if(isset($prdSearch)){
                                    $searchPrd = ProductSearches::find($prdSearch->id);
                                }else{
                                    $searchPrd = new ProductSearches();
                                }
                                $searchPrd->status = (count($warehouse_ids)==0)?'inactive':(($product->status=='active')?'active':'inactive');
                                $price = $productVariant->price;
                    
                                if($productVariant->sale_price!=0 && $productVariant->discount_expired!='yes')
                                {
                                    
                                    if($productVariant->discount_duration=='yes'){
                    
                                        $currentDate = Carbon::now()->format('d-m-Y H:i');
                    
                                        // Start and end date from user input or database
                                        $startDate = Carbon::parse($productVariant->discount_start_date)->format('d-m-Y H:i'); 
                                        $endDate = Carbon::parse($productVariant->discount_end_date)->format('d-m-Y H:i'); 
                    
                                        // Validate start date
                                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                                            $sale_price = $productVariant->sale_price;
                                            $discount = (($price-$sale_price)/$price)*100;
                                        } 
                    
                                    }else{
                                        $sale_price = $productVariant->sale_price;
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
                                $product_price = (!empty($sale_price) && ($sale_price>0))?$sale_price:$price;
                    
                                $images = json_decode($product->images, true);
                                $images = (count($images)!=0)?$images:json_decode($productVariant->images, true);
                    
                                $category_ids = Category::whereIn('id',explode(',',$product->category_ids))->whereStatus('active')->pluck('id')->toArray();
                                $searchPrd->product_id = $productVariant->product_id;
                                $searchPrd->slug = $product->slug;
                                $searchPrd->variant_id = $productVariant->id;
                                $searchPrd->images = json_encode($images, true);
                                $searchPrd->image1 = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                                $searchPrd->image2 = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
                                $searchPrd->product_name = $productVariant->product_name;
                                $searchPrd->price = $price;
                                $searchPrd->sale_price = $sale_price;
                                $searchPrd->search_price = $product_price;
                                $searchPrd->discount = ($discount!=0)?(round($discount)):0;
                                $searchPrd->discount_start_date = $productVariant->discount_start_date;
                                $searchPrd->discount_end_date = $productVariant->discount_end_date;
                                $searchPrd->cart_limit = $productVariant->cart_limit;
                                $searchPrd->description = $product->description;
                                $searchPrd->content = $product->content;
                                $searchPrd->product_created_at = $product->created_at;
                                $searchPrd->shipping_wide = $productVariant->shipping_wide;
                                $searchPrd->shipping_length = $productVariant->shipping_length;
                                $searchPrd->shipping_height = $productVariant->shipping_height;
                                $searchPrd->shipping_weight = $productVariant->shipping_weight;
                                $searchPrd->label = (isset($label->name))?$label->name:'';
                                $searchPrd->label_color_code = (isset($label->color))?$label->color:'';
                                $searchPrd->product_type = ProductVariant::whereProductId($product->id)->count();
                                $searchPrd->category_ids = ','.implode(',',$category_ids).',';
                                $searchPrd->warehouse_ids = ','.implode(',',$warehouse_ids).',';
                                $searchPrd->attribute_set_ids = implode(',',$attribute_set_ids);
                                $searchPrd->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
                                $searchPrd->review_count = $rating_count;
                                $searchPrd->save();
                            
                            }
                        }
    
                    }
                    
                    /** Removed warehouse and check it is in available
                     *  with any other zone  */
                    $removed_warehouse_ids = array_values(array_diff($prev_warehouse_ids, $new_warehouse_ids));
                    $removed_warehouseIds = (count($removed_warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $removed_warehouse_ids)) . ')(,|$)':'';
                
                    $zone_warehouse_ids = Zone::where('warehouse_ids', 'REGEXP', $removed_warehouseIds)->whereStatus('active')->pluck('warehouse_ids')->toArray();
                
                    
                    // Optionally, remove duplicates
                    $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));
                    $zone_warehouse_ids = array_filter(array_unique($flattenedArray));
                    $removed_warehouse_ids = array_values(array_diff($removed_warehouse_ids, $zone_warehouse_ids));
                
                    $warehouse_ids = (count($removed_warehouse_ids)!=0)?'(^|,)(' . implode('|', array_map('intval', $removed_warehouse_ids)) . ')(,|$)':'';
                    
                    $productVariants = ProductSearches::where('warehouse_ids', 'REGEXP', $warehouse_ids)->select('id','variant_id')->get();
    
                    foreach($productVariants as $variant)
                    {            
                        $zone_warehouse_ids = Zone::whereStatus('active')->pluck('warehouse_ids')->toArray();
                        $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));
                
                        // Optionally, remove duplicates
                        $zone_warehouse_ids = array_unique($flattenedArray);
                        $warehouse_ids = ProductStock::whereHas('warehouse', function($q){
                            $q->whereStatus('active');
                        })->whereProductVariantId($variant->variant_id)
                        ->whereIn('warehouse_id',$zone_warehouse_ids)
                        ->pluck('warehouse_id')->toArray();
    
                        $productVariant = ProductVariant::where('id',$variant->variant_id)->first();
                        ProductSearches::where('id',$variant->id)->update([
                            'warehouse_ids' => ','.implode(',',$warehouse_ids).',',
                            'status' => (count($warehouse_ids)==0)?'inactive':(($productVariant->product->status=='active')?'active':'inactive')
                        ]);            
                    }
                }
    
            }

        }elseif($this->type=='warehouse_update'){
            
            $productVariants = ProductSearches::whereRaw('FIND_IN_SET(?, warehouse_ids)', [$this->id])->select('id','variant_id')->get();

            foreach($productVariants as $variant)
            {            
                $zone_warehouse_ids = Zone::whereStatus('active')->pluck('warehouse_ids')->toArray();
                $flattenedArray = array_merge(...array_map(fn($item) => explode(',', $item), $zone_warehouse_ids));
        
                // Optionally, remove duplicates
                $zone_warehouse_ids = array_unique($flattenedArray);
                $warehouse_ids = ProductStock::whereHas('warehouse', function($q){
                    $q->whereStatus('active');
                })->whereProductVariantId($variant->variant_id)
                  ->whereIn('warehouse_id',$zone_warehouse_ids)
                  ->pluck('warehouse_id')->toArray();
    
                $productVariant = ProductVariant::where('id',$variant->variant_id)->first();
                ProductSearches::where('id',$variant->id)->update([
                    'warehouse_ids' => ','.implode(',',$warehouse_ids).',',
                    'status' => (count($warehouse_ids)==0)?'inactive':(($productVariant->product->status=='active')?'active':'inactive')
                ]);             
            }
        }elseif($this->type=='tax_update'){
            
            $product_ids = Product::where('tax_ids', $this->id)->pluck('id')->toArray();
            $productVariants = ProductSearches::whereIn('product_id',$product_ids)->select('id','variant_id')->get();
            
            foreach($productVariants as $variant)
            {
                
                $productVariant = ProductVariant::find($variant->variant_id);
                $product_id = $productVariant->product_id;
                $product = Product::find($product_id);
                $searchPrd = ProductSearches::find($variant->id);
                $price = $productVariant->price;
    
                if($productVariant->sale_price!=0 && $productVariant->discount_expired!='yes')
                {
                    if($productVariant->discount_duration=='yes'){    
                        $currentDate = Carbon::now()->format('d-m-Y H:i');    
                        // Start and end date from user input or database
                        $startDate = Carbon::parse($productVariant->discount_start_date)->format('d-m-Y H:i'); 
                        $endDate = Carbon::parse($productVariant->discount_end_date)->format('d-m-Y H:i'); 
    
                        // Validate start date
                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                            $sale_price = $productVariant->sale_price;
                            $discount = (($price-$sale_price)/$price)*100;
                        }     
                    }else{
                        $sale_price = $productVariant->sale_price;
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
                $product_price = (!empty($sale_price) && ($sale_price>0))?$sale_price:$price;
                $searchPrd->price = $price;
                $searchPrd->sale_price = $sale_price;
                $searchPrd->search_price = $product_price;
                $searchPrd->discount = ($discount!=0)?(round($discount)):0;
                $searchPrd->discount_start_date = $productVariant->discount_start_date;
                $searchPrd->discount_end_date = $productVariant->discount_end_date;
                $searchPrd->save();
            }
         
        }

    }

}
