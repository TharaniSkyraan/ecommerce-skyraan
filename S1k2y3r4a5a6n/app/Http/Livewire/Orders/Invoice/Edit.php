<?php

namespace App\Http\Livewire\Orders\Invoice;

use Livewire\Component;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Edit extends Component
{
    public $pdfBase64,$order_id;

    public function mount($order_id){
        $order = Order::find($order_id);
        $this->order_id = $order_id;
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();
        
        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $imageData;
        
        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
        $this->pdfBase64 = base64_encode($pdf->output());
        // dd($this->pdfBase64);

    }

    public function InvoiceDownload(){
        
        $order = Order::find($this->order_id);
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();
        
        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $imageData;

        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $order->invoice_number.'.pdf');
    }

    public function render()
    {
        return view('livewire.orders.invoice.edit');
    }
}
