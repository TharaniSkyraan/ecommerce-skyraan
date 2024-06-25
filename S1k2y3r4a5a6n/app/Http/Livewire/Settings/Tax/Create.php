<?php

namespace App\Http\Livewire\Settings\Tax;

use Livewire\Component;
use App\Models\Tax;

class Create extends Component
{
    public $tax_id,$name,$status,$slug,$percentage;

    /**
     * Store new or existing category in database
     */
    public function store()
    {

        $rules = [
            'name' => 'required|max:180|unique:taxes,name,'.$this->tax_id.',id,deleted_at,NULL',
            'percentage' => 'required|numeric|max:100', 
            'status' => 'required'
        ];
        $validateData = $this->validate($rules);
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $tax = Tax::updateOrCreate(
            ['id' => $this->tax_id],
            $validateData
        );
        $this->tax_id = $tax->id;
        session()->flash('message', 'Tax successfully saved.');
        
        return redirect()->to('admin/tax');
    }
    
    public function render()
    {
        return view('livewire.settings.tax.create');
    }

    public function mount($tax_id)
    {
        $this->tax_id = $tax_id;

        if(!empty($tax_id)){
            $tax = Tax::find($tax_id);
            $this->name = $tax->name;
            $this->percentage = $tax->percentage;
            $this->slug = $tax->slug;
            $this->status = $tax->status;
        }
    }


}
