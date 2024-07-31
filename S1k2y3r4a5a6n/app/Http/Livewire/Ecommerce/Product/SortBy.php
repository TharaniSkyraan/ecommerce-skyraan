<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;

class SortBy extends Component
{
    public $sort_by = 'all';

    protected $listeners = ['GetSortBySelf','PopSortBy'];

    public function GetSortBySelf($sort_by){
        $this->sort_by = $sort_by;
    }
    public function sortByUpdate($val){
        $this->sort_by = $val;
        
        $this->emit('GetSortBySelf',$this->sort_by);
        $this->emit('GetSortBy',$this->sort_by);
    }
    
    public function render()
    {
        return view('livewire.ecommerce.product.sort-by');
    }
}
