<?php

namespace App\Http\Livewire\Ecommerce;

use Livewire\Component;
use App\Mail\ContactAdminMail;
use App\Mail\ContactUserMail;


class Contactus extends Component
{
    public $email,$name,$feedback;

    public function contactus()
    {
        $data =  $this->validate([
            'email' => 'required|email',
            'name' => 'required|string|min:3|max:30',
            'feedback' => 'required|string|min:3|max:300',
        ]);
        // \Mail::send(new ContactAdminMail($data));
        \Mail::send(new ContactUserMail($data));
    }
    public function render()
    {
        return view('livewire.ecommerce.contactus');
    }
}
