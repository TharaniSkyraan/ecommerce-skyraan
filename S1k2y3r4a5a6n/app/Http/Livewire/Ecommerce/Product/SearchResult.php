<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;

class SearchResult extends Component
{
    
    public $is_mobile = '';
    public $type,$slug;
    public $loader = true;

    protected $listeners = ['screenWidthUpdated'=>'screenWidthUpdated','disbaleLoader'];

    public function screenWidthUpdated($width)
    {
        if($width<=991){
            $this->is_mobile = 'yes';
        }
    }
    public function disbaleLoader()
    {
        $this->loader = false;
    }

    public function mount($type,$slug){
        if($type!='category'&&$type!="search"&&$type!='collection'&&$type!='special'&&$type!='product-collection'){
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.ecommerce.product.search-result');
    }
}
