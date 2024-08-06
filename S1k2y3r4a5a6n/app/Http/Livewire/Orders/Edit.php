<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingStatus;
use App\Models\OrderShipment;
use App\Models\ShippingHistory;
use App\Models\OrderHistory;
use App\Models\ProductStock;
use App\Models\StockHistory;
use App\Models\ProductStockUpdateQuantityHistory;
use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Traits\OrderInvoice;

class Edit extends Component
{
    use OrderInvoice;

    public $order_id,$order,$order_status,$modalisOpen,$status,$shipment;

    public $statuses = [];

    protected $listeners = ['IsModalOpen','cancelOrder'];

    public function ShipmentStatusUpdate()
    {
        $shipping_id = $this->shipment->id;
        $this->status = OrderShipment::where('id',$shipping_id)->pluck('status')->first();
        $this->statuses = ShippingStatus::get();
        $this->IsModalOpen();
    }

    public function invoiceGenerate()
    {
        return $this->generateinvoice($this->order->id, 'preview');
    }
    
    public function UpdateStatus(){
        $this->validate(['status' => 'required']);
        $shipping_id = $this->shipment->id;
        OrderShipment::where('id',$shipping_id)->update(['status'=>$this->status]);
        if($this->status=='cancelled'){
            $description = 'Order cancelled because of shipping cancel.';
            $shipping_description = 'Shipping cancelled by admin';
        }else 
        if($this->status=='shipped'){
            $shipping_description = $description = 'Order is shipped successfully';
        }else
        if($this->status=='out_for_delivery'){
            $shipping_description = $description = 'Your product was out for delivery.';
        }else
        if($this->status=='delivered'){
            $shipping_description = $description = 'Order is delivered successfully';
        }else{
            $shipping_description = 'Your order is '.str_replace('_',' ',ucwords($this->status));
        }
        ShippingHistory::updateOrCreate(['order_id'=>$this->order_id,'user_id'=>$this->order->user_id,'action'=>$this->status,'shipment_id'=>$shipping_id],['description'=>$shipping_description]);
        
        if($this->status=='shipped'||$this->status=='delivered'||$this->status=='cancelled'||$this->status=='out_for_delivery'){
            $data['status'] = $this->status;
            $data['invoice_number'] = ($this->status=='delivered')?$this->generateInvoiceNumber($this->order_id):null;
            $data['invoice_date'] = ($this->status=='delivered')?Carbon::now():null;
            Order::where('id',$this->order_id)->update($data);            
            OrderHistory::updateOrCreate(['order_id'=>$this->order_id,'action'=>$this->status],['description'=>$description]);
           
            if(!empty($this->order->payments->charge_id) && $this->status=='cancelled'){
                try {
                    $amount = $this->order->payments->amount;
                    $api = new Api(config('shipping.razorpay.razorpay_key'), config('shipping.razorpay.razorpay_secret'));
                    $refund = $api->payment->fetch($this->order->payments->charge_id)->refund([
                        'amount' => $amount ? $amount * 100 : null  // Amount in paise
                    ]);
                    \Log::info('Refund successful: ' . $refund['id']);
                } catch (\Exception $e) {
                    \Log::info('Refund failed: ' . $e->getMessage());
                }
            }

            if($this->status=='cancelled')
            {
                $stockhistories = StockHistory::whereReferenceNumber($this->order_id)->whereStockType('order')->get();
        
                foreach($stockhistories as $stock_history)
                {

                    $stock_history =  StockHistory::find($stock_history->id)->update(['received_date' => Carbon::now(),'status' => 'cancelled']);

                    $quantity_update_histories = ProductStockUpdateQuantityHistory::whereHistoryId($stock_history->id)->get();

                    foreach($quantity_update_histories as $quantity_history)
                    {

                        $product_stock = ProductStock::whereWarehouseId($quantity_history->warehouse_id)
                                                    ->whereProductVariantId($quantity_history->product_variant_id)
                                                    ->first();

                        $product_stock = ProductStock::find($product_stock->id);
                        $available_stock = $product_stock->available_quantity;
                        $product_stock->available_quantity += $quantity_history->updated_quantity;
                        $product_stock->stock_status = ($product_stock->available_quantity<1)?'out_of_stock':'in_stock';
                        $product_stock->save();
                        
                        ProductStockUpdateQuantityHistory::create([
                            'history_id' => $stock_history->id,
                            'warehouse_id' => $quantity_history->warehouse_id,
                            'product_name' => $quantity_history->product_name,
                            'product_id' => $quantity_history->product_id,
                            'product_variant_id' => $quantity_history->product_variant_id,
                            'previous_available_quantity' => $available_stock,
                            'updated_quantity' => $quantity_history->updated_quantity,
                            'available_quantity' => $available_stock + $quantity_history->updated_quantity,
                        ]);

                    }

                }
            }

            if($this->status=='delivered')
            {
                $stockhistories = StockHistory::whereReferenceNumber($this->order_id)->whereStockType('order')->get();
        
                foreach($stockhistories as $stock_history)
                {
                    $stock_history =  StockHistory::find($stock_history->id)->update(['received_date' => Carbon::now(),'status' => 'delivered']);
                }
            }
            $this->getOrder();
        }else{
            $this->shipment = $this->order->shipment;
        }

        session()->flash('message', 'Shipping Status updated successfully.');
        $this->IsModalOpen();
    }

    public function confirmOrder(){
        $shipping_id = $this->shipment->id;
        Order::where('id',$this->order_id)->update(['status'=>'order_confirmed']);
        OrderHistory::updateOrCreate(['order_id'=>$this->order_id,'action'=>'order_confirmed'],['description'=>'Order request confirmed at '.Carbon::now()->format('d M y h:i A')]);
        ShippingHistory::updateOrCreate(['order_id'=>$this->order_id,'user_id'=>$this->order->user_id,'action'=>'order_confirmed','shipment_id'=>$shipping_id],['description'=>'Order request confirmed at '.Carbon::now()->format('d M y h:i A')]);
        $this->getOrder();
        session()->flash('message', 'Order confirmed.');
    }

    public function cancelOrder($reason=''){
        $shipping_id = $this->shipment->id;
      
        if(!empty($this->order->payments->charge_id)){
            try {
                $amount = $this->order->payments->amount;
                $api = new Api(config('shipping.razorpay.razorpay_key'), config('shipping.razorpay.razorpay_secret'));
                $refund = $api->payment->fetch($this->order->payments->charge_id)->refund([
                    'amount' => $amount ? $amount * 100 : null  // Amount in paise
                ]);
                \Log::info('Refund successful: ' . $refund['id']);
            } catch (\Exception $e) {
                \Log::info('Refund failed: ' . $e->getMessage());
            }
        }

        Order::where('id',$this->order_id)->update(['status'=>'cancelled']);
        OrderShipment::where('order_id',$this->order_id)->update(['status'=>'cancelled']);
        OrderHistory::updateOrCreate(['order_id'=>$this->order_id,'action'=>'cancelled'],['description'=>'Admin cancelled the order because of '.$reason.'.']);
        ShippingHistory::updateOrCreate(['order_id'=>$this->order_id,'user_id'=>$this->order->user_id,'action'=>'order_cancelled','shipment_id'=>$shipping_id],['description'=>'Admin cancelled the order because of '.$reason.'.']);
        
        $stockhistories = StockHistory::whereReferenceNumber($this->order_id)
                                      ->whereStockType('order')->get();
        
        foreach($stockhistories as $stock_history)
        {

            $stock_history =  StockHistory::find($stock_history->id)->update(['received_date' => Carbon::now(),'status' => 'cancelled']);

            $quantity_update_histories = ProductStockUpdateQuantityHistory::whereHistoryId($stock_history->id)->get();

            foreach($quantity_update_histories as $quantity_history)
            {

                $product_stock = ProductStock::whereWarehouseId($quantity_history->warehouse_id)
                                              ->whereProductVariantId($quantity_history->product_variant_id)
                                              ->first();

                $product_stock = ProductStock::find($product_stock->id);
                $available_stock = $product_stock->available_quantity;
                $product_stock->available_quantity += $quantity_history->updated_quantity;
                $product_stock->stock_status = ($product_stock->available_quantity<1)?'out_of_stock':'in_stock';
                $product_stock->save();
                
                ProductStockUpdateQuantityHistory::create([
                    'history_id' => $stock_history->id,
                    'warehouse_id' => $quantity_history->warehouse_id,
                    'product_name' => $quantity_history->product_name,
                    'product_id' => $quantity_history->product_id,
                    'product_variant_id' => $quantity_history->product_variant_id,
                    'previous_available_quantity' => $available_stock,
                    'updated_quantity' => $quantity_history->updated_quantity,
                    'available_quantity' => $available_stock + $quantity_history->updated_quantity,
                ]);

            }

        }

        $this->getOrder();

        session()->flash('message', 'Order Cancelled.');
    }

    private function generateInvoiceNumber($counter){
        return 'INV-' . str_pad($counter, 12, '0', STR_PAD_LEFT);
    }

    private function getOrder(){
        $this->order = Order::findorfail($this->order_id);
        $this->order_status = $this->order->status;
        $this->shipment = $this->order->shipment;
    }
    
    public function IsModalOpen(){
        $this->modalisOpen = (empty($this->modalisOpen)?'show':'');
        $this->resetValidation('status');
    }

    public function mount($order_id)
    {
        $this->order_id = $order_id;
        $this->getOrder();
    }

    public function render()
    {
        return view('livewire.orders.edit');
    }
}
