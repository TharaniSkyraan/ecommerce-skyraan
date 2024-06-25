<?php

namespace App\Http\Livewire\Orders\Shipment;

use Livewire\Component;
use App\Models\OrderShipment;
use App\Models\ShippingStatus;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\ShippingHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Edit extends Component
{
    public $shipping_id,$shipment,$modalisOpen,$status,$order_status;

    public $statuses = [];

    protected $listeners = ['IsModalOpen'];
    public function ShipmentStatusUpdate()
    {
        $this->status = OrderShipment::where('id',$this->shipping_id)->pluck('status')->first();
        $this->statuses = ShippingStatus::get();
        $this->IsModalOpen();
    }
    
    public function invoiceGenerate(){
        
        $order = Order::find($this->shipment->order_id);
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
        OrderShipment::where('id',$this->shipping_id)->update(['status'=>$this->status]);
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
        ShippingHistory::updateOrCreate(['order_id'=>$this->shipment->order_id,'user_id'=>$this->shipment->user_id,'action'=>$this->status,'shipment_id'=>$this->shipping_id],['description'=>$shipping_description]);
        
        if($this->status=='shipped'||$this->status=='delivered'||$this->status=='cancelled'||$this->status=='out_for_delivery'){
            Order::where('id',$this->shipment->order_id)->update(['status'=>$this->status]);            
            OrderHistory::updateOrCreate(['order_id'=>$this->shipment->order_id,'action'=>$this->status],['description'=>$description]);
        }
        session()->flash('message', 'Shipping Status updated successfully.');
        $this->IsModalOpen();
    }
    
    public function IsModalOpen(){
        $this->modalisOpen = (empty($this->modalisOpen)?'show':'');
        $this->resetValidation('status');
    }
    
    public function mount($shipping_id){
        $this->shipping_id = $this->shipping_id;
    }
    public function render()
    {
        $this->shipment = OrderShipment::find($this->shipping_id);
        return view('livewire.orders.shipment.edit');
    }
}
