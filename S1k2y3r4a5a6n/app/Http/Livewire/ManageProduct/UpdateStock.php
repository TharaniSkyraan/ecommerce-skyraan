<?php

namespace App\Http\Livewire\ManageProduct;

use Livewire\Component;

class UpdateStock extends Component
{
    protected $listeners = ['SelectProduct'];
    public function SelectProduct()
    {
        dd('rr');
    }

    public function render()
    {
        return view('livewire.manage-product.update-stock');
    }
}
