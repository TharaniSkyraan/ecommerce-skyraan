<?php

namespace App\Http\Livewire\Ecommerce\Layout;

use Livewire\Component;
use App\Models\NofityAvailableProduct;

class NotifyMe extends Component
{
    public $email,$product_id,$product_variant_id;

    protected $listeners = ['NotifyProduct'];

    public function NotifyMe()
    {
        $this->success = '';
        if(\Auth::check())
        {
            $this->email = auth()->user()->email;
        }
        $this->validate([
            'email' => 'required|string|max:180|email',
        ]);
        NofityAvailableProduct::updateOrCreate(
            ['email' => $this->email,'product_id' => $this->product_id,'product_variant_id' => $this->product_variant_id],
        );

        $this->success = 'success';
        
        $this->emit('NotifySuccessToast','success');
    } 

    public function NotifyProduct($product_id,$variant_id)
    {
        $this->success = '';
        $this->product_id = $product_id;
        $this->product_variant_id = $variant_id;
        
        if(\Auth::check())
        {
            $this->NotifyMe();
        }
    }
    
    public function render()
    {
        return view('livewire.ecommerce.layout.notify-me');
    }

}
