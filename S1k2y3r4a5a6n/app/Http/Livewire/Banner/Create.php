<?php

namespace App\Http\Livewire\Banner;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Banner;
use App\Models\Product;

class Create extends Component
{
    use WithFileUploads;
    public $banner_id,$name,$image,$temp_image,$description,$status,$slug,$query,$promotion_banner,$special_product;
    public $product_type = 'single';
    public $product_ids = [];
    public $products = [];
    public $selected_products = [];
    public $suggesstion = false;
    protected $listeners = ['suggestion' => 'suggestion','unsetsuggestion' => 'unsetsuggestion'];
    /**
     * Suggested product
     */
    public function updatedQuery(){
        if(!empty($this->query)){
            $this->products = Product::where('name', 'like', "%{$this->query}%")->limit(5)->get();
        }
    }
    public function updatedProductType(){
        if($this->product_type=='single'){
            $this->product_ids = (count($this->product_ids)>1)?[$this->product_ids[0]]:$this->product_ids;
            $this->selected_products = Product::Find($this->product_ids);
        }
    }
    public function updatedSpecialProduct(){
        $this->promotion_banner = '';
    }
    public function updatedPromotionBanner(){
        $this->special_product = '';
    }

    public function suggestion(){
        $this->suggesstion = true;
    }

    public function unsetsuggestion(){
        $this->suggesstion = false;
    }
    /**
     * Add selected product
     */
    public function addProduct($product_id){
        if($this->product_type=='single'){
            $this->product_ids = [$product_id];
        }else{
            array_push($this->product_ids, $product_id);
        }
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
     * Store new or existing category in database
     */
    public function store()
    {

        $rules = [
            'name' => 'required|max:180|unique:banners,name,'.$this->banner_id.',id,deleted_at,NULL',
            'description' => 'required|max:180', 
            'product_ids' => 'required',
            'status' => 'required',
            'product_type' => 'required'
        ];
        if(!empty($this->banner_id))
        {
            if(!empty($this->image)){
                $rules['image'] = 'required|image|max:2048';
            }
        }else{
            $rules['image'] = 'required|image|max:2048';
        }
        $validateData = $this->validate($rules);
        if(!empty($this->image)){
            $filename = $this->image->store('banners','public');
            $validateData['image'] = $filename;
        }
        $validateData['promotion_banner'] = ($this->promotion_banner=='yes')?'yes':'no';
        $validateData['special_product'] = ($this->special_product=='yes')?'yes':'no';
        $validateData['product_ids'] = ','.implode(',',$this->product_ids).',';
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $banner = Banner::updateOrCreate(
            ['id' => $this->banner_id],
            $validateData
        );
        $this->banner_id = $banner->id;
        session()->flash('message', 'Banner successfully saved.');
        
        return redirect()->to('admin/banner');
    }
    
    public function render()
    {
        return view('livewire.banner.create');
    }

    public function mount($banner_id)
    {
        $this->banner_id = $banner_id;
        $this->products = Product::limit(5)->get();

        if(!empty($banner_id)){
            $banner = Banner::find($banner_id);
            $this->name = $banner->name;
            $this->temp_image = $banner->image;
            $this->description = $banner->description;
            $this->product_ids =  array_values(array_filter(explode(',',$banner->product_ids)));
            $this->slug = $banner->slug;
            $this->promotion_banner = ($banner->promotion_banner=='yes')?'yes':'';
            $this->special_product = ($banner->special_product=='yes')?'yes':'';
            $this->status = $banner->status;
            $this->product_type = $banner->product_type;
            $this->selected_products = Product::Find($this->product_ids);
        }
        
    }


}