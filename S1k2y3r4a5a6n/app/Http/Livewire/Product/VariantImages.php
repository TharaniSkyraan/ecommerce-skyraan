<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;


class VariantImages extends Component
{
    use WithFileUploads;
    
    protected $listeners = ['addvariantImageRow','resetvariantImageInputvalues','editVariantImages'];
    
    public $variantImageList = [];
     
    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'variantImageList.')) {
            $index = explode('.', $propertyName)[1];
            $path = "temp\product\/variant_images\/".\Auth::guard('admin')->id();
            $this->variantImageList[$index]['image'] = $this->variantImageList[$index]['image']->store($path,'public');
            $variantImageList = $this->variantImageList;
            array_pop($variantImageList);
            $this->emit('GetVariantImages', $variantImageList);
        }
    }
        
    public function addvariantImageRow()
    {
        $this->variantImageList[] = ['id' => '', 'image' => null, 'temp_image' => null];
    }

    public function mount()
    {
        $this->variantImageList[] = ['id' => '', 'image' => null, 'temp_image' => null];
    }

    public function removevariantImageRow($index)
    {
        unset($this->variantImageList[$index]);
        $this->variantImageList = array_values($this->variantImageList); // Re-index the array
        $variantImageList = $this->variantImageList;
        array_pop($variantImageList);
        $this->emit('GetVariantImages', $variantImageList);
    }

    public function resetvariantImageInputvalues(){      
        $this->reset(['variantImageList']);  
        $this->variantImageList[] = ['id' => '', 'image' => null, 'temp_image' => null];
    }  

    public function editVariantImages($imageData){  
        // if(count($imageData)!=0){
        // }
        $this->variantImageList = $imageData;
        $this->addvariantImageRow();
    }

    public function render()
    {
        return view('livewire.product.variant-images');
    }


}
