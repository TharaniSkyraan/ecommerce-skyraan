<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeSet;
use App\Models\ProductAttributeSet;
use App\Models\SubProduct;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Label;
use App\Models\Tax;
use App\Models\Setting;
use App\Models\Collection;
use Carbon\Carbon;

class Create extends Component
{
    use WithFileUploads;
    public $product_id, $description, $content, $name, $status, $slug, $brand, $tax_ids, $query, $crosssellingquery, $label_id;
    public $variant_id, $sku, $price, $cart_limit, $sale_price, $cost_per_item, $shipping_wide, $shipping_length, $shipping_height, $shipping_weight, $images, $is_default, $discount_duration, $discount_start_date, $discount_end_date;
    public $category_ids = [];
    public $collection_ids = [];
    public $imageList = [];
    public $variantImageList = [];
    public $attr_ids = [];
    public $selectedattrList = []; 
    public $previousSelectedattrList = [];
    public $attrModalisOpen = '';
    public $variantModalisOpen = '';
    public $product_variant_type = 'single';
    
    public $product_variant_menus = ['id'=>'Id', 'image'=>'Image', 'price'=>'Price', 'is_default'=>'Is Default', 'action'=>'Operation'];
    public $default_menu = ['id'=>'Id', 'image'=>'Image', 'price'=>'Price', 'is_default'=>'Is Default', 'action'=>'Operation'];
    protected $listeners = ['initialize','attropenModal','variantopenModal','closeModal','GetImages','GetVariantImages','suggestion','unsetsuggestion','cross_selling_suggestion','unset_cross_selling_suggestion','GetDate'];

    public $productVariantList = [];
    public $previousProductVariantList = [];   
    public $iseditproductVariant = '';

    public $product_ids = [];
    public $products = [];
    public $selected_products = [];
    public $suggesstion = false;

    public $cross_selling_product_ids = [];
    public $selected_cross_selling_products = [];
    public $cross_selling_suggesstion = false;

    // Product Variant Input Fields/

    public $productAttributeList = [];

    public function attropenModal(){
        $this->attrModalisOpen = (empty($this->attrModalisOpen)?'show':'');
    }

    public function variantopenModal(){
        $this->variantModalisOpen = (empty($this->variantModalisOpen)?'show':'');
    }

    public function closeModal(){
        // if(($this->attrModalisOpen=='show' && count($this->selectedattrList)==0) || ($this->variantModalisOpen=='show' && count($this->productVariantList)==0)){
        //     $this->reset(['product_variant_type','is_default']);
        // }
        $this->variantModalisOpen = $this->attrModalisOpen = '';
        $this->emit('resetvariantImageInputvalues');
        $this->resetproductVariants();
    }

    public function updatedProductVariantType(){
        $this->selectedattrList = $this->productVariantList = [];

        // $this->selectedattrList = $this->previousSelectedattrList;
        // $this->productVariantList = $this->previousProductVariantList;
        
        // if($this->product_variant_type=='single'){
        //     $this->emit('variantopenModal');
        // }if($this->product_variant_type=='multiple'){
        //     $this->emit('attropenModal');
        // }

    }

    public function addAttr()
    {
        $attr_ids = array_keys(array_filter(array_filter($this->attr_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        $this->previousSelectedattrList = $this->selectedattrList = Attribute::find($attr_ids);

        $product_variant_menus = $this->default_menu;

        $newmenus = [];        

        foreach ($product_variant_menus as $key => $value) {
            
            foreach($this->selectedattrList as $key1 => $attribute){
                
                $KeyInserted = false;

                if ($key === "price" && !isset($product_variant_menus[$attribute->slug]) && !$KeyInserted) {
                    $newmenus[$attribute->slug] = ucwords($attribute->name);
                    $KeyInserted = true;
                    if($key1 == (count($this->selectedattrList)-1)){
                        $newmenus[$key] = $value;
                    }
                }else{                    
                    $newmenus[$key] = $value;
                }

            }        
        
        }

        $this->product_variant_menus = (count($newmenus)==0)?$product_variant_menus:$newmenus;

        $this->emit('closeModal');

    }

    // Add variant
    public function addVariant()
    {
   
        $rules = [
            'sku' => 'required|max:180|unique:product_variants,sku,'.($this->variant_id??'null').',id,deleted_at,NULL',
            'price' => 'required|min:1|numeric', 
            'cart_limit' => 'required|min:1|numeric', 
            'shipping_length' => 'required|min:1|numeric',
            'shipping_wide' => 'required|min:1|numeric',
            'shipping_height' => 'required|min:1|numeric',
            'shipping_weight' => 'required|min:1|numeric',
            // 'cost_per_item' => 'required|min:1|numeric',
        ];
        
        if(!empty($this->sale_price)){    
            $rules['sale_price']='required|max:'.$this->price.'|numeric';
        }
        if(($this->discount_duration=='yes')){
            $rules['discount_start_date']='required';
            $rules['discount_end_date']='required';
        }
        foreach($this->selectedattrList as $index => $attribute){
            $rules["productAttributeList.$index.{$attribute->slug}"] = 'required';
        }

        $this->validate($rules);
        
        foreach($this->selectedattrList as $index => $attribute){
            
            $attribute_set = AttributeSet::whereSlug($this->productAttributeList[$index]["{$attribute->slug}"])->whereAttributeId($attribute->id)->first();
            $productVariantList["{$attribute->slug}"][] = $attribute_set->name??$this->productAttributeList[$index]["{$attribute->slug}"];
            $productVariantList["{$attribute->slug}"][] = $this->productAttributeList[$index]["{$attribute->slug}"];
        }
        $productVariantList['id'] = $this->variant_id;
        $productVariantList['sku'] = $this->sku;
        $productVariantList['price'] = $this->price; 
        $productVariantList['sale_price'] = $this->sale_price; 
        $productVariantList['cart_limit'] = $this->cart_limit; 
        $productVariantList['discount_start_date'] = ($this->discount_duration=='yes')?Carbon::parse($this->discount_start_date)->format('Y-m-d H:i'):null;
        $productVariantList['discount_end_date'] = ($this->discount_duration=='yes')?Carbon::parse($this->discount_end_date)->format('Y-m-d H:i'):null;
        $productVariantList['discount_duration'] = ($this->discount_duration=='yes')?'yes':'no'; 
        $productVariantList['cost_per_item'] = $this->cost_per_item; 
        $productVariantList['shipping_wide'] = $this->shipping_wide; 
        $productVariantList['shipping_height'] = $this->shipping_height; 
        $productVariantList['shipping_weight'] = $this->shipping_weight; 
        $productVariantList['shipping_length'] = $this->shipping_length; 
        $productVariantList['images'] = $this->variantImageList;
        $productVariantList['image'] = !empty($productVariantList['images'][0]['image'])?$productVariantList['images'][0]['image']:(!empty($productVariantList['images'][0]['temp_image'])?$productVariantList['images'][0]['temp_image']:'');
        //  asset('storage').'/'.$productVariantList['images'][0]['image']
       
        if($this->iseditproductVariant!=''){
            $this->productVariantList[$this->iseditproductVariant] = $productVariantList;
        }else{
            $this->productVariantList[] = $productVariantList;
        }
        if($this->product_variant_type=='single'){
            $this->is_default = 0;
        }
        $this->emit('closeModal');
    }

    public function editProductVariant($index){

        $this->iseditproductVariant = $index;
        
        $productVariantList = $this->productVariantList[$index];

        foreach($this->selectedattrList as $index => $attribute){
            $this->productAttributeList[$index]["{$attribute->slug}"] = $productVariantList["{$attribute->slug}"][1]??'';
        }
        $this->variant_id = $productVariantList['id'];
        $this->sku = $productVariantList['sku'];
        $this->price = $productVariantList['price']; 
        $this->discount_start_date = ($productVariantList['discount_duration']=='yes')?Carbon::parse($productVariantList['discount_start_date'])->format('d-m-Y H:i'):'';
        $this->discount_end_date = ($productVariantList['discount_duration']=='yes')?Carbon::parse($productVariantList['discount_end_date'])->format('d-m-Y H:i'):'';
        $this->discount_duration = ($productVariantList['discount_duration']=='yes')?'yes':'';
        $this->sale_price = $productVariantList['sale_price']; 
        $this->cart_limit = $productVariantList['cart_limit']; 
        $this->cost_per_item = $productVariantList['cost_per_item']; 
        $this->shipping_wide = $productVariantList['shipping_wide']; 
        $this->shipping_height = $productVariantList['shipping_height']; 
        $this->shipping_weight = $productVariantList['shipping_weight']; 
        $this->shipping_length = $productVariantList['shipping_length']; 
        $this->variantImageList = $productVariantList['images'];

        $this->emit('editVariantImages', $this->variantImageList);
        $this->emit('variantopenModal');

    }
    
    public function deleteProductVariant($index){        
        unset($this->productVariantList[$index]);
        $this->productVariantList = array_values($this->productVariantList); 
    }
    
    private function resetproductVariants(){
        $this->reset(['variant_id','sku','price','sale_price','cart_limit','discount_start_date','discount_end_date','discount_duration','cost_per_item','productAttributeList',
                       'shipping_wide','shipping_height','shipping_weight','shipping_length','variantImageList','iseditproductVariant']); 

    }

    // Related Products

    public function updatedQuery(){
        if(!empty($this->query)){
            $this->products = Product::where('name', 'like', "%{$this->query}%")->limit(5)->get();
        }
    }
    
    public function suggestion(){
        $this->suggesstion = true;
    }
    
    public function unsetsuggestion(){
        $this->suggesstion = false;
    }
    
    // Cross Selling Product

    public function updatedCrosssellingquery(){
        if(!empty($this->crosssellingquery)){
            $this->products = Product::where('name', 'like', "%{$this->crosssellingquery}%")->limit(5)->get();
        }
    }

    public function cross_selling_suggestion(){
        $this->cross_selling_suggesstion = true;
    }

    public function unset_cross_selling_suggestion(){
        $this->cross_selling_suggesstion = false;
    }
    /**
     * Add selected product
     */
    public function addProduct($product_id){
        array_push($this->product_ids, $product_id);
        $this->selected_products = Product::Find($this->product_ids);
    }

    /**
     * Remove selected product
     */
    public function removeProduct($product_id){
        $this->product_ids = array_diff($this->product_ids, [$product_id]);
        $this->selected_products = Product::Find($this->product_ids);
    }

    /**
     * Add selected product
     */
    public function addcrossSellingProduct($product_id){
        array_push($this->cross_selling_product_ids, $product_id);
        $this->selected_cross_selling_products = Product::Find($this->cross_selling_product_ids);
    }

    /**
     * Remove selected product
     */
    public function removecrossSellingProduct($product_id){
        $this->cross_selling_product_ids = array_diff($this->cross_selling_product_ids, [$product_id]);
        $this->selected_cross_selling_products = Product::Find($this->cross_selling_product_ids);
    }

    public function store()
    {
        $attr_ids = array_keys(array_filter(array_filter($this->attr_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        $category_ids = array_keys(array_filter(array_filter($this->category_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));
        
        $collection_ids = array_keys(array_filter(array_filter($this->collection_ids, function($key) {
            return $key !== "";
        }, ARRAY_FILTER_USE_KEY)));

        
        // $tax_ids = array_keys(array_filter(array_filter($this->tax_ids, function($key) {
        //     return $key !== "";
        // }, ARRAY_FILTER_USE_KEY)));

        // Your store logic here
        $rules = [
            'name' => 'required',
            'description' => 'required', 
            'status' => 'required', 
            'imageList' => 'required', 
            'productVariantList' => 'required', 
            'is_default' => 'required', 
            'product_variant_type' => 'required', 
        ];
        foreach($this->selectedattrList as $attr){
            $rules['productVariantList.*.'.$attr->slug] = 'required';
        }
        $this->validate($rules);
        
        $product = new Product();
        if(!empty($this->product_id)){
            $product = Product::find($this->product_id);
        }
        $product->name  = $this->name;
        $product->slug  = str_replace(' ','-',strtolower($this->name));
        $product->description  = $this->description;
        $product->status  = $this->status;
        $product->content  = $this->content??' ';
        $product->related_product_ids  = ','.implode(',',$this->product_ids).',';
        $product->cross_selling_product_ids  = ','.implode(',',$this->cross_selling_product_ids).',';
        $product->brand  = $this->brand??0;
        $product->tax_ids  = $this->tax_ids??0;
        $product->category_ids  = ','.implode(',',$category_ids).',';
        $product->label_id  = !empty($this->label_id)?$this->label_id:0;
        $product->attribute_ids = ','.implode(',',$attr_ids).',';
        if(!empty($this->product_id) && ($this->product_variant_type != $product->product_variant_type))
        {
            ProductVariant::whereProductId($this->product_id)->delete();
        }
        $product->product_variant_type = $this->product_variant_type;

        $productimages = [];
        foreach ($this->imageList as $images) {
            // Move the image from temporary to permanent storage
            if(!empty($images['image'])){
                \Storage::disk('public')->move($images['image'], 'product/images/' . basename($images['image']));
                $productimages[] = 'product/images/' . basename($images['image']);
            }else if(!empty($images['temp_image'])){
                $productimages[] = $images['temp_image'];
            }
        }
        $product->images = json_encode($productimages);
        $product->save();

        $product_id = $product->id;

        foreach($collection_ids as $collection_id)
        {

            $collection = Collection::where('id',$collection_id)->where('product_ids', 'REGEXP', $product_id)->first();
            
            if(!isset($collection)){

                $collection = Collection::find($collection_id);
                $product_ids = array_filter(array_values(explode(',',$collection->product_ids)));
                array_push($product_ids, $product_id);
                $collection->product_ids = ','.implode(',', $product_ids).',';
                $collection->save();
            }

        }

        foreach($this->productVariantList as $index => $variant)
        {
            $productvariantimages = [];
            if(empty($variant['id'])){
                $productVariant = new ProductVariant();
            }else{
                $productVariant = ProductVariant::find($variant['id']);
            }

            $tax = Tax::find($this->tax_ids);
            $product_price = (!empty($variant['sale_price']) && ($variant['sale_price']>0))?$variant['sale_price']:$variant['price'];

            $productVariant->product_id = $product_id;
            $productVariant->sku = $variant['sku'];
            $productVariant->price = $variant['price'];
            $productVariant->sale_price = (!empty($variant['sale_price']))?$variant['sale_price']:0;
            $productVariant->cart_limit = $variant['cart_limit'];
            $productVariant->search_price = $product_price;
            $productVariant->discount_duration = $variant['discount_duration'];
            $productVariant->discount_start_date = $variant['discount_start_date']??null;
            $productVariant->discount_end_date = $variant['discount_end_date']??null;
            $productVariant->shipping_wide   = (!empty($variant['shipping_wide']))?$variant['shipping_wide']:0;
            $productVariant->shipping_height = (!empty($variant['shipping_height']))?$variant['shipping_height']:0;
            $productVariant->shipping_weight = (!empty($variant['shipping_weight']))?$variant['shipping_weight']:0;
            $productVariant->shipping_length = (!empty($variant['shipping_length']))?$variant['shipping_length']:0;
            $productVariant->is_default = ($index==$this->is_default)?'yes':'no';
            foreach ($variant['images'] as $images) {
                // Move the image from temporary to permanent storage
                if(!empty($images['image'])){
                    \Storage::disk('public')->move($images['image'], 'product/variant/images/' . basename($images['image']));
                    $productvariantimages[] = 'product/variant/images/' . basename($images['image']);
                }else if(!empty($images['temp_image'])){
                    $productvariantimages[] = $images['temp_image'];
                }
            }
            $productVariant->images = json_encode($productvariantimages);
            $productVariant->save();

            $product_variant_id = $productVariant->id;
            foreach($this->selectedattrList as $index => $attribute){
                $attribute_set = AttributeSet::whereSlug($variant[$attribute->slug][1])
                                             ->whereAttributeId($attribute->id)
                                             ->first();
                if(isset($attribute_set)){

                    $checkexist = ProductAttributeSet::whereProductVariantId($product_variant_id)
                                                     ->whereAttributeId($attribute->id)->first();
           

                    if(isset($checkexist)){
                        $product_attribute_set = ProductAttributeSet::find($checkexist->id);
                    }else{
                        $product_attribute_set = new ProductAttributeSet();
                    }
                    $product_attribute_set->product_id = $product_id;
                    $product_attribute_set->product_variant_id = $product_variant_id;
                    $product_attribute_set->attribute_id = $attribute_set->attribute_id;
                    $product_attribute_set->attribute_set_id = $attribute_set->id;
                    $product_attribute_set->attribute_set_slug = $attribute_set->slug;
                    $product_attribute_set->save();
                }
                
            }
            $productVariant = ProductVariant::find($product_variant_id);
            $productVariant->product_name = $productVariant->product->name.(!empty($productVariant->getSetAttribute())? '/'.$productVariant->getSetAttribute() : '');
            $productVariant->save();

        }

        session()->flash('message', 'Product successfully saved.');

        return redirect()->to('admin/product');
    }

    public function GetImages($imagedata)
    {
        $this->imageList = $imagedata;
    }

    public function GetVariantImages($imagedata)
    {
        $this->variantImageList = $imagedata;
    }

    public function GetDate($start, $end){
        $this->discount_start_date = $start;
        $this->discount_end_date = $end;
    }

    public function mount($product_id)
    {
        $this->product_id = $product_id;

        if(!empty($product_id))
        {
            $product = Product::find($product_id);
            $collection_ids = Collection::where('product_ids', 'REGEXP', $product_id)->pluck('id')->toArray();
            
            $this->collection_ids = array_fill_keys($collection_ids, true);;
            $this->name         = $product->name;
            $this->description  = $product->description;
            $this->status       = $product->status;
            $this->content      = $product->content??' ';
            $this->product_variant_type = $product->product_variant_type;
            $this->product_ids  = array_filter(array_values(explode(',',$product->related_product_ids)));
            $this->cross_selling_product_ids  = array_filter(array_values(explode(',',$product->cross_selling_product_ids)));
            $this->brand         = ($product->brand!=0)?$product->brand:null;
            $this->category_ids  = array_fill_keys(explode(',',$product->category_ids), true);
            $this->label_id      = ($product->label_id!=0)?$product->label_id:'';
            $this->tax_ids       = $product->tax_ids;
            $this->attr_ids      = array_fill_keys(explode(',',$product->attribute_ids), true);
            $imageList     = json_decode($product->images, true);
            $is_default = null;
            // PRoduct Image Fetch
            $this->imageList = array_map(function ($imagelist) {

                $imglist['temp_image'] = ($imagelist !=' ')?$imagelist:'';
                $imglist['image'] = '';
                $imglist['id'] = '';
                return $imglist;

            }, $imageList);
            // Attribute ids
            if(!empty($this->attr_ids)){
                $this->addAttr();
            }
            
            // product variants
            $productVariant = ProductVariant::whereProductId($product_id)->get()->toArray();

            $this->previousProductVariantList = $this->productVariantList = array_map(function ($productvariant, $key) use(&$is_default) {
                
                $imageList     = json_decode($productvariant['images'], true);

                $productvariant['images'] = array_map(function ($imagelist) {

                    $imglist['temp_image'] = ($imagelist !=' ')?$imagelist:'';
                    $imglist['image'] = '';
                    $imglist['id'] = '';
                    return $imglist;
    
                }, $imageList);
                $productvariant['image'] = !empty($productvariant['images'][0]['temp_image'])?$productvariant['images'][0]['temp_image']:'';

                if($productvariant['is_default']=='yes'){
                    $is_default = $key;
                }
                foreach($this->selectedattrList as $index => $attribute){
                    $product_attribute_set = ProductAttributeSet::whereProductVariantId($productvariant['id'])->whereAttributeId($attribute->id)->first();
                    if(isset($product_attribute_set)){
                        $productvariant["{$attribute->slug}"][] = ucwords($product_attribute_set->attribute_set->name??'');
                        $productvariant["{$attribute->slug}"][] = $product_attribute_set->attribute_set_slug??'';    
                    }
                 }
                
                return $productvariant;

            }, $productVariant, array_keys($productVariant));
            
            $this->is_default = $is_default;

            $this->selected_cross_selling_products = Product::Find($this->cross_selling_product_ids);
            $this->selected_products = Product::Find($this->product_ids);
          
        }else{
            $this->tax_ids = Setting::pluck('default_tax')->first();
        }

        $this->auth_id = \Auth::guard('admin')->id();
        $this->products = Product::limit(5)->get();
        $this->categories = Category::whereStatus('active')->whereNULL('parent_id')->orderBy('sort','asc')->get();
        $this->collections = Collection::whereStatus('active')->orderBy('sort','asc')->get();
        $this->labels = Label::whereStatus('active')->get();
        $this->brands = Brand::whereStatus('active')->orderBy('sort','asc')->get();
        $this->taxes = Tax::whereStatus('active')->get();
        $this->attrList = Attribute::orderBy('sort','asc')->get();
    }

    public function initialize(){
        if(!empty($this->product_id)){
            $this->emit('editProductImages', $this->imageList);            
        }
    }

    public function render()
    {
        return view('livewire.product.create');
    }

}