<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Order;
use App\Traits\OrderInvoice;

class OrderDetail extends Component
{
    use OrderInvoice;

    public $ordId,$ordRef,$order,$screenWidth;

    protected $queryString = ['ordId','ordRef'];

    protected $listeners = ['screenSizeCaptured' => 'handleScreenSize'];

    public function handleScreenSize($width)
    {
        $this->screenWidth = $width;
    }

    public function invoiceGenerate(){        
        return $this->generateinvoice($this->order->id, 'download');
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
