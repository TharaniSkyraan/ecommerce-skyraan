<?php

namespace App\Http\Livewire\CancelReasons;

use Livewire\Component;
use App\Models\CancelReason;

class Create extends Component
{
    public $cancel_reason_id,$reason;

    /**
     * Store new or existing category in database
     */
    public function store()
    {

        $rules = [
            'reason' => 'required|max:255|min:3|unique:cancel_reasons,name,'.$this->cancel_reason_id.',id',
        ];
        
        $validateData = $this->validate($rules);
        $validateData['name'] = $this->reason;
        unset($validateData['reason']);
        $cancel_reason = CancelReason::updateOrCreate(
            ['id' => $this->cancel_reason_id],
            $validateData
        );
        $this->cancel_reason_id = $cancel_reason->id;
        session()->flash('message', 'CancelReason successfully saved.');
        
        return redirect()->to('admin/cancel_reasons');
    }
    
    public function render()
    {
        return view('livewire.cancel-reasons.create');
    }

    public function mount($cancel_reason_id)
    {
        $this->cancel_reason_id = $cancel_reason_id;

        if(!empty($cancel_reason_id)){
            $cancel_reason = CancelReason::find($cancel_reason_id);
            $this->reason = $cancel_reason->name;
        }
    }


}