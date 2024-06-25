<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;

class GridFilter extends Component
{
    public $view = 'two';
    
    public function dataView($view){
        $this->view = $view;
        $this->emit('GetView',$view);
    }

    public function render()
    {
        return view('livewire.ecommerce.product.grid-filter');
    }
}
