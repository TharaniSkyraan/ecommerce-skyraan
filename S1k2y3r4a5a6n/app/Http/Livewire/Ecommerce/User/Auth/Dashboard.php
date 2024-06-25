<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
class Dashboard extends Component
{
    public $purchased_product ,$order_items;

    public function mount(){

        $user_id = auth()->user()->id??0;
        $startDate = Carbon::now()->subMonth()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $this->order_items  = OrderItem::whereHas('orders',function($q){
            $q->whereUserId(auth()->user()->id);                                                                                                                                        
        })->latest()
        ->take(4)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get()->each(function ($items) {
            $images = json_decode($items->product_image, true);
            $items->image1 = isset($images[0]) ? asset('storage').'/'.$images[0] : asset('asset/home/default-hover1.png');
           $items->append(['order_code']);
        });
       
        $this->recommeded_products = Product::take(12)->get()->each(function ($items) {
            $images = json_decode($items->images, true);
            $items->image1 = isset($images[0]) ? asset('storage').'/'.$images[0] : asset('asset/home/default-hover1.png');
        });

    }

    public function render()
    {
        return view('livewire.ecommerce.user.auth.dashboard');
    }

}
