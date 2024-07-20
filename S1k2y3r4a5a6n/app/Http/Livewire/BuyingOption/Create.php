<?php

namespace App\Http\Livewire\BuyingOption;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\BuyingOption;

class Create extends Component
{
    use WithFileUploads;
    public $buying_option_id,$name,$image,$temp_image,$description,$status,$slug;
    public $feature_type='all';

    /**
     * Store new or existing category in database
     */
    
    public function store()
    {

        $rules = [
            'name' => 'required|max:180|unique:buying_options,name,'.$this->buying_option_id.',id,deleted_at,NULL',
            'description' => 'required|max:180', 
            'status' => 'required'
        ];
        if(!empty($this->buying_option_id))
        {
            if(!empty($this->image)){
                $rules['image'] = 'required|image|max:1024|mimes:svg';
            }
        }else{
            $rules['image'] = 'required|image|max:1024|mimes:svg';
        }
        $validateData = $this->validate($rules);
        if(!empty($this->image)){
            $filename = $this->image->store('buying_options','public');
            $validateData['image'] = $filename;
        }
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $validateData['feature_type'] = $this->feature_type;
        $buying_option = BuyingOption::updateOrCreate(
            ['id' => $this->buying_option_id],
            $validateData
        );
        $this->buying_option_id = $buying_option->id;

        session()->flash('message', 'Buying Option successfully saved.');
        
        return redirect()->to('admin/buying-option');
    }
    
    public function render()
    {
        return view('livewire.buying-option.create');
    }

    public function mount($buying_option_id)
    {
        $this->buying_option_id = $buying_option_id;

        if(!empty($buying_option_id)){
            $buying_option = BuyingOption::find($buying_option_id);
            $this->name = $buying_option->name;
            $this->temp_image = $buying_option->image;
            $this->description = $buying_option->description;
            $this->slug = $buying_option->slug;
            $this->status = $buying_option->status;
            $this->feature_type = $buying_option->feature_type;
        }
    }
}