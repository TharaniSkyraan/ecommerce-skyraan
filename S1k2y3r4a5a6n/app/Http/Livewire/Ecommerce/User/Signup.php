<?php

namespace App\Http\Livewire\Ecommerce\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\ZoneConfig;
use App\Mail\WelcomeMail;

class Signup extends Component
{
    use ZoneConfig;

    public $email,$password,$phone,$name,$password_strength,$verified_status,$verified_phone_number;
    public $phone_validate;
    
    protected $listeners = ['PhoneNumberVerified'];

    public function updated($propertyName)
    {
        $ipData = \Session::get('ip_config');
        if ($propertyName === 'phone') {
            $this->resetValidation('phone');
            $this->phone_validate = false;
            $this->verified_status = '';
    
            if ($ipData && isset($ipData->code)) {
                $validationRules = [
                    'phone' => 'required|numeric|phone:' . $ipData->code . '|unique:users',
                ];
            } else {
                $validationRules = [
                    'phone' => 'required|numeric|unique:users',
                ];
            }
    
            $this->validateOnly($propertyName, $validationRules, [
                'phone.required' => 'Phone number is required',
                'phone.numeric' => 'Please enter a valid phone number',
                'phone.unique' => 'The given phone number already exists',
                'phone.phone' => 'Please enter a valid phone number',
            ]);
    
            if ($this->phone != $this->verified_phone_number) {
                $this->phone_validate = true;
                $this->verified_status = '';
            } else {
                $this->phone_validate = false;
                $this->verified_status = 'verified';
            }
        } elseif ($propertyName === 'email') {
            $this->resetValidation('email');
            $this->validateOnly($propertyName, [
                'email' => 'required|string|max:180|email|unique:users',
            ],[                
                'email.unique' => 'The given email already exist',
                'email.required' => 'Email id is required',
                'email.email' => 'Please enter valid phone number',
                'email.max' => 'Email must be less than 30 characters',
            ]);
        } elseif ($propertyName === 'name') {
            $this->resetValidation('name');
            $this->validateOnly($propertyName, [
                'name' => 'required|string|min:3|max:30',
            ],[
                'name.required' => 'Name is required',
                'name.min' => 'Name must be at least 3 characters',
                'name.max' => 'Name must be less than 30 characters.',
            ]);
        } elseif ($propertyName === 'password') {
            $this->resetValidation('password');
            $this->validateOnly($propertyName, [
                'password' => 'required',
            ],[
                'password.required' => 'Password is required',
            ]);
        }
    }

    public function PhoneNumberVerified(){
        $this->verified_status = 'verified';
        $this->verified_phone_number = $this->phone;
        $this->phone_validate = false;
    }

    public function verify_otp()
    {

        $this->emit('SendOtp',$this->phone);

    }

    public function signup(){
        
        $ipData = \Session::get('ip_config');
        
        $validatedData = $this->validate([
            'phone' => 'required|numeric|phone:'.$ipData->code.'|unique:users',
            'email' => 'required|string|max:180|email|unique:users',
            'name' => 'required|string|min:3|max:180',
            'password' => 'required'
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric'=> 'Please enter valid Phone Number',
            'phone.unique' => 'The given phone number already exist',
            'phone.phone' => 'Please enter valid Phone Number',
            'email.unique' => 'The given email already exist',
            'email.required' => 'Email id is required',
            'password.required' => 'Password is required',
            'name.required' => 'Name is required',
        ]);
        if(empty($this->verified_status)){
            $this->verified_status = 'Please verify phone number.';
        }
        if($this->password_strength!='weak' && $this->verified_status=='verified'){

            $validatedData['password'] = Hash::make($validatedData['password']);

            User::create($validatedData);
            
            Mail::to($user->email)->send(new WelcomeMail($user->name, $user->email));

            $credentials = [
                'email' => $this->email,
                'password' => $this->password,
            ];
    
            if (auth()->attempt($credentials)) {             
                // return redirect()->to($this->redirect_url);
                $this->ipzone();
                $this->emit('SignupComplete', '');
            }
        }
    }

    public function render()
    {
        return view('livewire.ecommerce.user.signup');
    }

}
