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

class DamageStockUpdate extends Component
{
    public $warehouses,$reference_number,$warehouse_id,$suggestion,$query;
    public $selected_products = [];
    public $products = [];
    public $suggesstion = false;
    protected $listeners = ['suggestion', 'unsetsuggestion', 'resetInputvalues','OpenStocksLimit'];

    public function GenerateReference(){
        $counter = StockHistory::select('id')->orderBy('id','desc')->first();
        $this->reference_number = 'DMG-'.Carbon::now()->format('Yhis').'-'. str_pad($counter->id??1, 4, '0', STR_PAD_LEFT);
    }
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
            $available_quantity = $available_stock->available_quantity??0;
            $data['product_name']= $productVariant->product->name.(!empty($productVariant->getSetAttribute())? '/'.$productVariant->getSetAttribute() : '');
            $data['product_id'] = $productVariant->product_id;
            $data['variant_id'] = $productVariant->id;
            $data['available_stock'] = $available_quantity;
            $data['product_stock_id'] = $available_stock->id??'';
            $data['warehouse_id'] = $this->warehouse_id;
            $data['quantity'] = ($available_quantity!=0)?1:0;
            $this->selected_products[$index] = $data;  

        }

    }
    public function updatedWarehouseId()
    {
        $this->selected_products = [];
    }

    public function OpenStocksLimit(){
        $this->GenerateReference();
    }

    public function damageProductUpdate(){
        
        $rules = [
            'warehouse_id' => 'required',
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
        $messages = ['selected_products.required' => 'Product required.'];
        
        $validateData = $this->validate($rules, $messages);
        $date = Carbon::now();
        $history = StockHistory::Create([
            'stock_type' => 'damage',
            'reference_number' => $this->reference_number,
            'warehouse_from_id' => 0,
            'warehouse_to_id' => $this->warehouse_id,
            'sent_date' => $date,
            'received_date' => $date,
            'status' => 'removed'
        ]);
        foreach ($this->selected_products as $product) {

            if(isset($product['quantity']) && $product['quantity']>=1)
            {
                
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
                        'product_name' => $product['product_name'],
                    ]
                );
            }

        }

        $this->resetInputvalues();
        $this->emit('Success','');
    }

    public function resetInputvalues(){  
        $this->resetValidation();
        $this->reset(['reference_number','warehouse_id','selected_products']);  
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

    public function render()
    {
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $this->warehouses = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->get(); 
        }
        else{
            $this->warehouses = Warehouse::all();
        }
        return view('livewire.manage-product.damage-stock-update');
    }
}