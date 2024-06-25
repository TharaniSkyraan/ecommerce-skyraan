<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;


class Images extends Component
{
    use WithFileUploads;
    
    protected $listeners = ['addRow','editProductImages'];
    

    public $imageList = [];
     
    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'imageList.')) {
            $index = explode('.', $propertyName)[1];
            $path = "temp\product\images\/".\Auth::guard('admin')->id();
            $this->imageList[$index]['image'] = $this->imageList[$index]['image']->store($path,'public');
            $this->emit('GetImages', $this->imageList);
        }
    }
        
    public function addRow()
    {
        $this->imageList[] = ['id' => '', 'image' => null, 'temp_image' => null];
    }

    public function mount()
    {
        $this->imageList[] = ['id' => '', 'image' => null, 'temp_image' => null];
        
    }

    public function removeRow($index)
    {
        unset($this->imageList[$index]);
        $this->imageList = array_values($this->imageList); // Re-index the array
        $imageList = (count($this->imageList)==1)?[]:$this->imageList;
        $this->emit('GetImages', $imageList);
    }
    
    public function editProductImages($imageData){ 

        if(count($imageData)!=0){
            $this->imageList = $imageData;
        }
        $this->emit('addRow');

    }

    public function render()
    {
    
        return view('livewire.product.images');
    }


}
