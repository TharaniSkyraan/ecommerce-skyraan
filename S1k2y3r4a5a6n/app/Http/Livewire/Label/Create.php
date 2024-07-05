<?php

namespace App\Http\Livewire\Label;

use Livewire\Component;
use App\Models\Label;

class Create extends Component
{
    public $label_id,$name,$status,$slug,$color;

    /**
     * Store new or existing category in database
     */
    public function store()
    {

        $rules = [
            'name' => 'required|max:16|unique:labels,name,'.$this->label_id.',id,deleted_at,NULL',
            'color' => 'required|max:7', 
            'status' => 'required'
        ];
        
        $validateData = $this->validate($rules);
        $validateData['slug'] = str_replace(' ','-',strtolower($this->name));
        $label = Label::updateOrCreate(
            ['id' => $this->label_id],
            $validateData
        );
        $this->label_id = $label->id;
        session()->flash('message', 'Label successfully saved.');
        
        return redirect()->to('admin/label');
    }
    
    public function render()
    {
        return view('livewire.label.create');
    }

    public function mount($label_id)
    {
        $this->label_id = $label_id;

        if(!empty($label_id)){
            $label = Label::find($label_id);
            $this->name = $label->name;
            $this->slug = $label->slug;
            $this->color = $label->color;
            $this->status = $label->status;
        }
    }


}