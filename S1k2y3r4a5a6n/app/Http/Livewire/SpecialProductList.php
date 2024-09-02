<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\SpecialProduct;

class SpecialProductList extends Component
{
    public $query,$special_id;
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
            $this->products = Product::where('name', 'like', "%{$this->query}%")->whereNotIn('id',$this->product_ids)->limit(5)->get();
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
        array_push($this->product_ids, $product_id);
        $this->selected_products = Product::Find($this->product_ids);
        
        $speciaclproducts = SpecialProduct::updateOrCreate(
            ['id' => $this->special_id],
            ['product_ids'=>implode(',',$this->product_ids)]
        );
        $this->special_id = $speciaclproducts->id??'';
    }

    /**
     * Remove selected product
     */
    public function removeProduct($product_id){
        $this->product_ids = array_diff($this->product_ids, [$product_id]);
        $this->selected_products = Product::Find($this->product_ids);
        $speciaclproducts = SpecialProduct::updateOrCreate(
            ['id' => $this->special_id],
            ['product_ids'=>implode(',',$this->product_ids)]
        );
        $this->special_id = $speciaclproducts->id??'';
    }

    public function mount(){        
        $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('special-products');

        $speciaclproducts = SpecialProduct::first();
        $this->special_id = $speciaclproducts->id??'';
        if(isset($speciaclproducts)){
            $this->selected_products = Product::Find(explode(',',$speciaclproducts->product_ids));
            $this->product_ids = explode(',',$speciaclproducts->product_ids);
        }else{
            $this->products = Product::limit(5)->whereNotIn('id',$this->product_ids)->get();
        }
    }

    public function render()
    {

        return view('livewire.special-product-list');
    }
}
