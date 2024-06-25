<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingStatus;
use App\Models\OrderShipment;
use App\Models\ShippingHistory;
use App\Models\OrderHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Edit extends Component
{
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

    public function invoiceGenerate(){
        
        $order = $this->order;
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();
        
        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $imageData;
        
        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
        $pdfBase64 = base64_encode($pdf->output());

        $this->emit('previewInvoice',$pdfBase64);

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

    public function cancelOrder(){
        $shipping_id = $this->shipment->id;
        Order::where('id',$this->order_id)->update(['status'=>'cancelled']);
        OrderShipment::where('order_id',$this->order_id)->update(['status'=>'cancelled']);
        OrderHistory::updateOrCreate(['order_id'=>$this->order_id,'action'=>'cancelled'],['description'=>'Order cancelled by admin.']);
        ShippingHistory::updateOrCreate(['order_id'=>$this->order_id,'user_id'=>$this->order->user_id,'action'=>'order_cancelled','shipment_id'=>$shipping_id],['description'=>'Order cancelled by admin.']);
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
