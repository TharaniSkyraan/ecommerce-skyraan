<?php

namespace App\Http\Livewire\Orders\Invoice;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use App\Traits\OrderInvoice;

class Edit extends Component
{
    use OrderInvoice;
    public $pdfBase64,$order_id;

    public function mount($order_id){        
        $this->order_id = $order_id;
        return $this->generateinvoice($order_id);
    }

    public function InvoiceDownload(){
        return $this->generateinvoice($this->order_id, 'download');
    }
    public function render()
    {
        return view('livewire.orders.invoice.edit');
    }
}
