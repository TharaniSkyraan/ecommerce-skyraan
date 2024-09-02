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

class ModifyUpdateStock extends Component
{
    public $stocks,$reference_number;
    public $selected_products = [];
    protected $listeners = ['resetInputvalues'];

    public function updatedReferenceNumber()
    {
        $histories = StockHistory::whereReferenceNumber($this->reference_number)->whereStockType('upload')->get();
        $this->selected_products = [];

        foreach($histories as $history){

            $product_histories = ProductStockUpdateQuantityHistory::whereHistoryId($history->id)->get();
            
            foreach($product_histories as $product_history)
            {
    
                $updated_quantity = ProductStockUpdateQuantityHistory::whereWarehouseId($product_history->warehouse_id)
                                                                        ->whereProductVariantId($product_history->product_variant_id)
                                                                        ->whereHas('history', function($q) use($history){
                                                                            $q->whereId($history->id)
                                                                              ->whereStockType('modify');
                                                                        })->pluck('updated_quantity')
                                                                          ->first();
                $productVariant = ProductVariant::find($product_history->product_variant_id);
                
                $available_stock = ProductStock::whereWarehouseId($product_history->warehouse_id)
                                               ->whereProductVariantId($product_history->product_variant_id)
                                               ->select('id','available_quantity')->first();
                
                $available_quantity = $available_stock->available_quantity??0;
                $data['product_name']= $productVariant->product->name.(!empty($productVariant->getSetAttribute())? '/'.$productVariant->getSetAttribute() : '');
                $data['product_id'] = $productVariant->product_id;
                $data['variant_id'] = $productVariant->id;
                $data['available_stock'] = $available_quantity;
                $data['upload_stock'] = $product_history->updated_quantity;
                $data['last_modified_quantity'] = $updated_quantity??0;
                $data['product_stock_id'] = $available_stock->id??'';
                $data['warehouse_name'] = $product_history->warehouse->name??'';
                $data['warehouse_id'] = $product_history->warehouse_id;
                $data['quantity'] = 0;
                
                $index = $productVariant->id.'-'.$product_history->warehouse_id;
                $this->selected_products[$index] = $data;  
    
            }
        }


    }

    public function productModify(){
        
        $rules = [
            'reference_number' => 'required',
            'selected_products' => 'required'
        ];        
        $validateData = $this->validate($rules);

        $date = Carbon::now();
        foreach ($this->selected_products as $product) {

            if($product['quantity']!=0)
            {

                $history =  StockHistory::updateOrCreate([
                                'reference_number' => $this->reference_number,
                                'warehouse_to_id' => $product['warehouse_id'],
                                'stock_type' => 'modify',
                            ],[
                                'warehouse_from_id' => 0,
                                'received_date' => $date,
                                'sent_date' => $date,
                                'status' => 'received'
                            ]);
                $quantity = ($product['quantity'] > $product['upload_stock'])? ($product['quantity'] - $product['upload_stock']) :($product['upload_stock'] - $product['quantity']);
                $available_quantity = ($product['quantity'] > $product['upload_stock'])? ($product['available_stock'] + $quantity) : ($product['available_stock'] - $quantity);
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

                // Product stock upload
                $productStock = ProductStock::updateOrCreate([
                        'warehouse_id' => $product['warehouse_id'],
                        'product_variant_id' => $product['variant_id']
                    ],
                    [
                        'available_quantity' => $available_quantity,
                        'product_id' => $product['product_id'],
                        'stock_status' => ($available_quantity==0)?'out_of_stock':'in_stock',
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
        $this->reset(['reference_number','selected_products']);  
    }   

    public function decreaseQuantity($index){
        if($this->selected_products[$index]['quantity']>0){
           $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['quantity'] - 1;
        }
    }

    public function increaseQuantity($index){
        $quantity = $this->selected_products[$index]['quantity'] + 1;
        if($this->selected_products[$index]['available_stock']>=$quantity && $this->selected_products[$index]['upload_stock']>=$quantity){
            $this->selected_products[$index]['quantity'] = $quantity;
        }
    }

    public function updateQuantity($index){
        if(($this->selected_products[$index]['upload_stock'] < $this->selected_products[$index]['quantity'] || $this->selected_products[$index]['available_stock'] < $this->selected_products[$index]['quantity'])){
            if($this->selected_products[$index]['available_stock'] <= $this->selected_products[$index]['upload_stock']){
                $this->selected_products[$index]['quantity'] = ($this->selected_products[$index]['available_stock'] < $this->selected_products[$index]['quantity'])?$this->selected_products[$index]['available_stock']:$this->selected_products[$index]['upload_stock'];
            }
            if($this->selected_products[$index]['available_stock'] >= $this->selected_products[$index]['upload_stock']){
                $this->selected_products[$index]['quantity'] = ($this->selected_products[$index]['upload_stock'] < $this->selected_products[$index]['quantity'])?$this->selected_products[$index]['upload_stock']:$this->selected_products[$index]['available_stock'];
            }
        }
    }
    public function render()
    {
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
            $this->stocks = StockHistory::whereDoesntHave('modify')->whereIn('warehouse_from_id', $warehouse_ids)->whereStockType('upload')->get();
        }
        else{
            $this->stocks = StockHistory::whereDoesntHave('modify')->whereStockType('upload')->get();
        }
        return view('livewire.manage-product.modify-update-stock');
    }
}