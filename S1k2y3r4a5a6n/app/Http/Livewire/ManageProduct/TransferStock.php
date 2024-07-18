<?php

namespace App\Http\Livewire\ManageProduct;
use App\Models\Warehouse;
use Livewire\Component;
use App\Models\ProductStock;
use App\Models\StockHistory;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductStockUpdateQuantityHistory;
use Carbon\Carbon;

class TransferStock extends Component
{
    public $warehouses,$query,$image,$warehouse_id,$warehouse_to_id,$reference_number,$action,$suggestion;
    public $products = [];
    public $selected_products = [];
    public $suggesstion = false;
    protected $listeners = ['SelectProduct','suggestion', 'unsetsuggestion', 'resetInputvalues','OpenStockLimit'];

    public function updatedQuery(){
        if(!empty($this->query)){
            $query = $this->query;
            $this->products = ProductVariant::with('product')
            ->where('product_name', 'like', "%{$query}%")
            ->orwhere(function($q1) use($query){
                $q1->whereHas('product', function($q) use($query){
                    $q->where('name', 'like', "%{$query}%");
                });
            })->get();
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
        $index = $productVariant->id.'-'.$this->warehouse_id;
        
        if(isset($this->selected_products[$index]))
        {
            unset($this->selected_products[$index]);
        }else
        {
        
            $available_stock = ProductStock::whereWarehouseId($this->warehouse_id)->whereProductVariantId($variant_id)->select('id','available_quantity')->first();

            $data['product_name']= $productVariant->product->name.(!empty($productVariant->getSetAttribute())? '/'.$productVariant->getSetAttribute() : '');
            $data['product_id'] = $productVariant->product_id;
            $data['variant_id'] = $productVariant->id;
            $data['available_stock'] = $available_stock->available_quantity??0;
            $data['product_stock_id'] = $available_stock->id??'';
            $data['warehouse_id'] = $this->warehouse_id;
            $data['quantity'] = ($available_stock->available_quantity!=0 && isset($available_stock->available_quantity))?1:0;
            $this->selected_products[$index] = $data;  

        }

    }
    public function updatedWarehouseId()
    {
        $this->selected_products = [];
    }

    public function OpenStockLimit($action,$ids=""){
        $this->action=$action;
        if($action != "new")
        {
            $ids = explode(',',$ids);
            $this->$action=$action;
            $available_stocks = ProductStock::find($ids);

            foreach($available_stocks as $available_stock)
            {
                $data['product_name']= $available_stock->product_name??'';
                $data['product_id'] = $available_stock->product_id??'';
                $data['variant_id'] = $available_stock->product_variant_id??'';
                $data['available_stock'] = $available_stock->available_quantity??0;
                $data['product_stock_id'] = $available_stock->id??'';
                $data['warehouse_id'] = $available_stock->warehouse_id??'';
                $data['warehouse_name'] = $available_stock->warehouse->name??'';
                $data['quantity'] = ($available_stock->available_quantity!=0 && isset($available_stock->available_quantity))?1:0;
                $index = $available_stock->product_variant_id;
                $this->selected_products[$index.'-'.$available_stock->warehouse_id] = $data;
            }

        }
    }

    public function productUpdate(){

        $rules = [
            'warehouse_to_id' => 'required',
            'reference_number' => 'required',
            'selected_products' => ['required', function ($attribute, $value, $fail) {
                                        if (!is_array($value)) {
                                            $fail('The selected products must be an array.');
                                            return;
                                        }
                                        
                                        $filtered = array_filter($value, function($product) {
                                            return $product['quantity'] > 0;
                                        });
                                        
                                        if (count($filtered) === 0) {
                                            $fail('Selected products must have at least one item with quantity greater than zero.');
                                        }
                                    }]
                                ];
        $messages = ['selected_products.required' => 'Product required to transfer.'];
        if($this->action=='new'){
            $rules['warehouse_id'] = 'required|not_in:'.$this->warehouse_to_id;
            $messages['warehouse_id.not_in'] = ['The From warehouse and To warehouse must not be the same.'];
        }

        $validateData = $this->validate($rules, $messages);
        $date = Carbon::now();
        
        if($this->warehouse_id)
        {
            $history = StockHistory::Create([
                'stock_type' => 'transfer',
                'reference_number' => $this->reference_number,
                'warehouse_from_id' => $this->warehouse_id,
                'warehouse_to_id' => $this->warehouse_to_id,
                'sent_date' => $date,
                'status' => 'sent'
            ]);
        }
        foreach ($this->selected_products as $product) {

            if(isset($product['quantity']) && $product['quantity']>=1)
            {
                
                if($this->warehouse_id==null)
                {                
                    $history =  StockHistory::updateOrCreate([
                                    'reference_number' => $this->reference_number,
                                    'warehouse_from_id' => $product['warehouse_id'],
                                    'stock_type' => 'transfer',
                                ],[
                                    'warehouse_to_id' => $this->warehouse_to_id,
                                    'sent_date' => $date,
                                    'status' => 'sent'
                                ]);
                }
                $available_quantity = $product['available_stock'] - $product['quantity'];
                // Product Stock Quantity Update History
                $quantity_update_history = ProductStockUpdateQuantityHistory::create([
                    'history_id' => $history->id,
                    'warehouse_id' => $product['warehouse_id'],
                    'product_name' => $product['product_name'],
                    'product_id' => $product['product_id'],
                    'product_variant_id' => $product['variant_id'],
                    'previous_available_quantity' => $product['available_stock'],
                    'updated_quantity' => $product['quantity'],
                    'available_quantity' => $available_quantity,
                ]);

                $productStock = ProductStock::updateOrCreate([
                        'warehouse_id' => $product['warehouse_id'],
                        'product_variant_id' => $product['variant_id']
                    ],
                    [
                        'available_quantity' => $available_quantity,
                        'product_id' => $product['product_id'],
                        'stock_status' => ($available_quantity!=0)?'in_stock':'out_of_stock',
                    ]
                );
            }

        }

        $this->resetInputvalues();
        $this->emit('Success','');
    }

    public function resetInputvalues(){  
        $this->resetValidation();
        $this->reset(['warehouse_id','warehouse_to_id','reference_number','products','selected_products','query','suggesstion']);  
    }   

    public function decreaseQuantity($index){
        if($this->selected_products[$index]['quantity']>1){
           $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['quantity'] - 1;
        }
    }

    public function increaseQuantity($index){
        $quantity = $this->selected_products[$index]['quantity'] + 1;
        if($this->selected_products[$index]['available_stock']>=$quantity){
            $this->selected_products[$index]['quantity'] = $quantity;
        }
    }

    public function updateQuantity($index){
        if($this->selected_products[$index]['available_stock'] < $this->selected_products[$index]['quantity']){
            $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['available_stock'];
        }
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
        return view('livewire.manage-product.transfer-stock');
    }
}