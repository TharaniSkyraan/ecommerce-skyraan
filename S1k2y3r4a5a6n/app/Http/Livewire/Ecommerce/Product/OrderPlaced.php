<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Order;

class OrderPlaced extends Component
{
    
    public function mount($code){
        $this->order = Order::whereCode($code)->first();
    }
    public function render()
    {
        return view('livewire.ecommerce.product.order-placed');
    }
}
