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
        $data['order']['prininword'] = ucwords($this->numToWordsRec($data['order']['total_amount']));
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
