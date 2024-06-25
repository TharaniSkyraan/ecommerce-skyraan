<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use App\Models\Order;

class OrderDetail extends Component
{
    
    public $ordId,$ordRef,$order,$screenWidth;

    protected $queryString = ['ordId','ordRef'];

    protected $listeners = ['screenSizeCaptured' => 'handleScreenSize'];

    public function handleScreenSize($width)
    {
        $this->screenWidth = $width;
    }

    public function invoiceGenerate(){
        
        $order = $this->order;
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();
        $siteSetting = Setting::first();        
        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $imageData;

        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $order->invoice_number.'.pdf');
        // return $pdf->download('document.pdf');

    }
    public function mount(){
        $orderDate = Carbon::parse($this->ordRef);
        $this->order = Order::where('code',$this->ordId)
                      ->where('user_id',auth()->user()->id)
                      ->where('created_at',$orderDate)
                      ->first();
        if(!isset($this->order)){
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.ecommerce.user.auth.order-detail');
    }
}
