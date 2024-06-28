<?php

namespace App\Http\Livewire\ManageProduct;
use App\Models\Warehouse;
use Livewire\Component;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;

class UpdateStock extends Component
{
    public $warehouses,$query,$image,$warehouse_id,$action,$suggestion;
    public $products = [];
    public $selected_products = [];
    public $suggesstion = false;
    protected $listeners = ['SelectProduct','suggestion' => 'suggestion','unsetsuggestion' => 'unsetsuggestion', 'resetInputvalues','OpenUpdatestock'];

    public function updatedQuery(){
        if(!empty($this->query)){
            $this->products = ProductVariant::with('product')
                            ->where('sku', 'like', "%{$this->query}%")->get();
        }
    }

    public function suggestion(){
        $this->suggesstion = true;
    }

    public function unsetsuggestion(){
        $this->suggesstion = false;
    }

    public function ProductsArray($variant_id)
    {
        $this->validate([
            'warehouse_id' => 'required',
        ]);
     
        $productVariant = ProductVariant::find($variant_id);

        $available_stock = ProductStock::whereWarehouseId($this->warehouse_id)->whereProductVariantId($variant_id)->select('id','available_quantity')->first();

        $data['product_name']= $productVariant->product->name.(!empty($productVariant->getSetAttribute())? '/'.$productVariant->getSetAttribute() : '');
        $data['product_id'] = $productVariant->product_id;
        $data['variant_id'] = $productVariant->id;
        $data['available_stock'] = $available_stock->available_quantity??0;
        $data['product_stock_id'] = $available_stock->id??'';
        $data['warehouse_id'] = $this->warehouse_id;
        $data['quantity'] = 1;
        $index = $productVariant->id;
        $this->selected_products[$index] = $data;
        // dd($this->selected_products[$index]);
        $this->suggestion = false;
        $this->query = '';
    }

    public function isSelected($variant_id)
    {
        return isset($this->selected_products[$variant_id]);
    }

    public function updatedWarehouseId()
    {
        $this->selected_products = [];
    }

    public function OpenUpdatestock($action,$id=""){
        $this->action=$action;
        // dd($this->$action);
        if($action == "update")
        {
            // dd($id);

            $this->$action=$action;
            $available_stock = ProductStock::find($id);
            // dd($available_stock);
            $data['product_name']= $available_stock->product_name??'';
            $data['product_id'] = $available_stock->product_id??'';
            $data['variant_id'] = $available_stock->product_variant_id??'';
            $data['available_stock'] = $available_stock->available_quantity??0;
            $data['product_stock_id'] = $available_stock->id??'';
            $data['warehouse_id'] = $available_stock->warehouse_id??'';
            $data['warehouse_name'] = $available_stock->warehouse->name??'';
            $data['quantity'] = 1;
            $index = $available_stock->product_variant_id;
            $this->selected_products[$index] = $data;
        }
    }

    public function productUpdate(){

        $rules = [
            'warehouse_id' => 'required'
        ];
        
        $validateData = $this->validate($rules);

        foreach ($this->selected_products as $product) {
            $productStock = ProductStock::updateOrCreate([
                    'warehouse_id' => $product['warehouse_id'],
                    'product_variant_id' => $product['variant_id']
                ],
                [
                    'warehouse_id' => $product['warehouse_id'],
                    'product_variant_id' => $product['variant_id'],
                    'available_quantity' => $product['quantity'],
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                ]
            );
        }
        session()->flash('message', 'Stock updated successfully.');
        $this->resetInputvalues();
        $this->emit('Success','');
    }

    public function resetInputvalues(){      
        $this->reset(['warehouse_id','products','selected_products','query','suggesstion']);  
    }   

    public function decreaseQuantity($index){
        if($this->selected_products[$index]['quantity']>1){
           $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['quantity'] - 1;
        }
    }

    public function increaseQuantity($index){
        $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['quantity'] + 1;
    }

    public function removeProduct($index)
    {
        unset($this->selected_products[$index]);
    }

    public function mount()
    {
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $this->warehouses = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->get(); 
        }
        else{
            $this->warehouses = Warehouse::all();
        }
    }

    public function render()
    {
        return view('livewire.manage-product.update-stock');
    }
}