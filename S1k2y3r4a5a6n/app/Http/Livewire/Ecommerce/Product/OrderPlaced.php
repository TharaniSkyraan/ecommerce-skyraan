<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\OrderItem;
use App\Models\Order;

class OrderPlaced extends Component
{
    
    public function mount($code){

        $order_ids = explode(',',$code);
        $this->order = Order::find($order_ids[0]);

        if(count($order_ids)==2){
            $this->orderitems = OrderItem::whereHas('orders', function($q){
                $q->whereUserId(auth()->user()->id);
            })->whereRaw('order_id BETWEEN ' . $order_ids[0]. ' AND ' . $order_ids[1] . '')->get();
            $this->total_amount = Order::whereRaw('id BETWEEN ' . $order_ids[0]. ' AND ' . $order_ids[1] . '')->whereUserId(auth()->user()->id)->sum('total_amount');
        }else{
            $this->orderitems = OrderItem::whereHas('orders', function($q){
                $q->whereUserId(auth()->user()->id);
            })->where('order_id',$order_ids[0])->get();
            $this->total_amount = Order::where('id',$order_ids[0])->whereUserId(auth()->user()->id)->sum('total_amount');
        }

    }
    public function render()
    {
        return view('livewire.ecommerce.product.order-placed');
    }
}
