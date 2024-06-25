<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Carbon\Carbon;

class DiscountDate extends Component
{
    public $discount_start_date,$discount_end_date;

    public function updatedDiscountStartDate()
    {
        $this->emit('GetDate',$this->discount_start_date,$this->discount_end_date);
    }
    public function updatedDiscountEndDate()
    {        
        $this->emit('GetDate',$this->discount_start_date,$this->discount_end_date);
    }
    public function mount($start, $end)
    {
        $this->discount_start_date = $start;
        $this->discount_end_date = $end;
        $this->today = Carbon::now()->format('d-m-Y H:i');
    }
    public function render()
    {
        return view('livewire.product.discount-date');
    }
}
