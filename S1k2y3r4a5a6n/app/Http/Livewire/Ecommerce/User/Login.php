<?php

namespace App\Http\Livewire\Ecommerce\User;

use Livewire\Component;
use App\Traits\ZoneConfig;
use Auth;

class Login extends Component
{

    use ZoneConfig;
    
    public $username,$password,$errorMessage,$success;

    public function signin()
    {

        if (preg_match('/^\+?[0-9()-]+$/', $this->username)) {            
            $this->validate([
                'username' => 'required|string|min:10|max:10|exists:users,phone',
                'password' => 'required' 
            ], [
                'username.min'=> 'Please enter valid Email Id/Phone Number',
                'username.max'=> 'Please enter valid Email Id/Phone Number',
                'username.required' => 'Please enter valid Email Id/Phone Number',
                'username.exists' => 'The given phone number doesn\'t exist',
                'password.required' => 'Password is required'
            ]);
            $credentials = [
                'phone' => $this->username,
                'password' => $this->password,
            ];
        } else {    
            $this->validate([
                'username' => 'required|string|email|exists:users,email',
                'password' => 'required' 
            ], [
                'username.email' => 'Please enter valid Email Id/Phone Number',
                'username.required' => 'Please enter valid Email Id/Phone Number',
                'username.exists' => 'The given email id doesn\'t exist',
                'password.required' => 'Password is required'
            ]);
            $credentials = [
                'email' => $this->username,
                'password' => $this->password,
            ];
        }

        if (auth()->attempt($credentials)) {             
            // return redirect()->to($this->redirect_url);
            $this->ipzone($this);
            $this->emit('updateCart', '');
        }else{
            $this->errorMessage = 'Invalid password.';
        }
    }

    public function render()
    {
        return view('livewire.ecommerce.user.login');
    }
}
