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
use Razorpay\Api\Api;

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
        $data['order']['prininword'] = ucwords($this->numToWordsRec($data['order']['total_amount']));
        
        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $imageData;
        
        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
        $pdfBase64 = base64_encode($pdf->output());

        $this->emit('previewInvoice',$pdfBase64);

    }
    
    function numToWordsRec($number) {
        $words = array(
            0 => 'zero', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five',
            6 => 'six', 7 => 'seven', 8 => 'eight',
            9 => 'nine', 10 => 'ten', 11 => 'eleven',
            12 => 'twelve', 13 => 'thirteen', 
            14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty',
            90 => 'ninety'
        );

        if ($number < 20) {
            return $words[$number];
        }

        if ($number < 100) {
            return $words[10 * floor($number / 10)] .
                ' ' . $words[$number % 10];
        }

        if ($number < 1000) {
            return $words[floor($number / 100)] . ' hundred ' 
                . $this->numToWordsRec($number % 100);
        }

        if ($number < 1000000) {
            return $this->numToWordsRec(floor($number / 1000)) .
                ' thousand ' . $this->numToWordsRec($number % 1000);
        }

        return $this->numToWordsRec(floor($number / 1000000)) .
            ' million ' . $this->numToWordsRec($number % 1000000);
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
            $order = Order::find($this->shipment->order_id);
            if(!empty($order->payments->charge_id) && $this->status=='cancelled'){
                try {
                    $amount = $order->payments->amount;
                    $api = new Api(config('shipping.razorpay.razorpay_key'), config('shipping.razorpay.razorpay_secret'));
                    $refund = $api->payment->fetch($order->payments->charge_id)->refund([
                        'amount' => $amount ? $amount * 100 : null  // Amount in paise
                    ]);
                    \Log::info('Refund successful: ' . $refund['id']);
                } catch (\Exception $e) {
                    \Log::info('Refund failed: ' . $e->getMessage());
                }
            }
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
