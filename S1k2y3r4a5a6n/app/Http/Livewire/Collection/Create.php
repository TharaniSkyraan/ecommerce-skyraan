<?php

namespace App\Http\Livewire\Collection;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Collection;
use App\Models\Product;

class Create extends Component
{
    use WithFileUploads;
    public $collection_id,$name,$image,$temp_image,$description,$status,$slug, $query;
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
            'name' => 'required|max:180|unique:collections,name,'.$this->collection_id.',id,deleted_at,NULL',
            'description' => 'required|max:180', 
            'product_ids' => 'required',
            'status' => 'required',
            'product_type' => 'required'
        ];
        if(!empty($this->collection_id))
        {
            if(!empty($this->image)){
                $rules['image'] = 'required|image|max:1024';
            }
        }else{
            $rules['image'] = 'required|image|max:1024';
        }
        $validateData = $this->validate($rules);
        if(!empty($this->image)){
            $filename = $this->image->store('collections','public');
            $validateData['image'] = $filename;
        }

        
        
        $validateData['product_ids'] = ','.implode(',',$this->product_ids).',';
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $collection = Collection::updateOrCreate(
            ['id' => $this->collection_id],
            $validateData
        );
        $this->collection_id = $collection->id;
        session()->flash('message', 'Collection successfully saved.');
        
        return redirect()->to('admin/collection');
    }
    
    public function render()
    {
        return view('livewire.collection.create');
    }

    public function mount($collection_id)
    {
        $this->collection_id = $collection_id;
        $this->products = Product::limit(5)->get();

        if(!empty($collection_id)){
            $collection = Collection::find($collection_id);
            $this->name = $collection->name;
            $this->temp_image = $collection->image;
            $this->description = $collection->description;
            $this->product_ids =  array_values(array_filter(explode(',',$collection->product_ids)));
            $this->slug = $collection->slug;
            $this->status = $collection->status;
            $this->product_type = $collection->product_type;
            $this->selected_products = Product::Find($this->product_ids);
        }
        
    }


}