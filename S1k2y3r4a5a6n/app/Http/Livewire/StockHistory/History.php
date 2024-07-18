<?php

namespace App\Http\Livewire\StockHistory;

use Livewire\Component;
use App\Models\StockHistory;
use App\Models\ProductStock;
use App\Models\Warehouse;
use App\Models\ProductStockUpdateQuantityHistory;
use Carbon\Carbon;

class History extends Component
{
    public $stock_history,$warehouse_ids,$history_id;
    
    protected $listeners = ['stockReceived'];

    public function stockReceived()
    {
        $stock_history = StockHistory::findOrFail($this->history_id);  
        $history_id = $stock_history->id;
        $warehouse_id = $stock_history->warehouse_to_id;
        $stock_history->status = 'received';
        $stock_history->received_date = Carbon::now();
        $stock_history->save();

        $products = ProductStockUpdateQuantityHistory::whereHistoryId($history_id)->get();
        foreach($products as $product)
        {

            $productStock = ProductStock::whereWarehouseId($warehouse_id)->whereProductVariantId($product->product_variant_id)->first();

            $quantity = $product->updated_quantity;
            $previous_available_quantity = $productStock->available_quantity??0;
            $available_quantity = $previous_available_quantity + $quantity;

            ProductStock::updateOrCreate([
                    'warehouse_id' => $warehouse_id,
                    'product_variant_id' => $product->product_variant_id
                ],
                [
                    'available_quantity' => $available_quantity,
                    'product_id' => $product->product_id,
                    'stock_status' => 'in_stock',
                ]
            );

            // Product Stock Quantity Update History
            ProductStockUpdateQuantityHistory::create([
                'history_id' => $history_id,
                'warehouse_id' => $warehouse_id,
                'product_name' => $product->product_name,
                'product_id' => $product->product_id,
                'product_variant_id' => $product->product_variant_id,
                'previous_available_quantity' => $previous_available_quantity,
                'updated_quantity' => $quantity,
                'available_quantity' => $available_quantity,
            ]);

        }

        session()->flash('message', 'Stock received successfully.');

    }

    public function mount($history_id){
        $admin_id = \Auth::guard('admin')->user()->id; 
        $this->history_id = $history_id;
        $this->warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray();
    }

    public function render()
    { 
        $this->stock_history = StockHistory::findOrFail($this->history_id);
        return view('livewire.stock-history.history');
    }
}
