<?php

namespace App\Http\Livewire\Ecommerce;

use Livewire\Component;
use App\Models\EmailUnsubscribe;
use App\Models\User;

class Unsubscribe extends Component
{
    
    public $reason,$reason_note,$email;

    public function store()
    {
        
        $validatedData = $this->validate([
            'reason' => 'required',
            'reason_note' => 'required_if:reason,other',
        ]);

        if($this->reason=='other'){
            $this->validate(['reason_note' => 'string|min:3|max:255']);
        }
        $user = User::whereEmail($this->email)->first();

        if(isset($user)){
            User::where('id',$user->id)->update(['subscription'=>'disabled']);
            EmailUnsubscribe::create(['user_id'=>$user->id,'notes'=>($this->reason=='other')?$this->reason_note:$this->reason]);
        }
        
        $this->emit('showToast', 'Unsubscribed successfully!.');

    }

    public function mount($email)
    {
        $this->email = $email;
        $this->reason = 'I no longer want to receive these emails';
    }

    public function render()
    {
        return view('livewire.ecommerce.unsubscribe');
    }
}
