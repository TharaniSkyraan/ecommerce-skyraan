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
use Razorpay\Api\Api;

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
