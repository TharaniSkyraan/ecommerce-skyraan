<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use App\Models\User;


class AccountSetting extends Component
{
    public $name,$email,$phone,$verified_phone_number;
    public $phone_validate;
    public $is_edit = false;
    public $verified_status = 'verified';

    protected $listeners = ['PhoneNumberVerified'];


    public function updated($propertyName)
    {
        $ipData = \Session::get('ip_config');
        
        // Dynamically adjust validation rules
        if ($propertyName === 'phone') {
            $this->resetValidation('phone');
            $this->phone_validate = false;
            $this->verified_status = '';
            $this->validateOnly($propertyName, [
                'phone' => 'required|numeric|phone:'.$ipData->code.'|unique:users,phone,'.auth()->user()->id.',id',
            ],[
                'phone.required' => 'Phone number is required',
                'phone.numeric'=> 'Please enter valid Phone Number',
                'phone.unique' => 'The given phone number already exist',
                'phone.phone' => 'Please enter valid Phone Number',
            ]);
            if($this->phone != $this->verified_phone_number){
                $this->phone_validate = true;
                $this->verified_status = '';
            }else{
                $this->phone_validate = false;
                $this->verified_status = 'verified';
            }
        } elseif ($propertyName === 'email') {
            $this->resetValidation('email');
            $this->validateOnly($propertyName, [
                'email' => 'required|string|max:180|email|unique:users,email,'.auth()->user()->id.',id',
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
    public function AccountUpdate(){

        $ipData = \Session::get('ip_config');
        
        $validatedData = $this->validate([
            'phone' => 'required|numeric|phone:'.$ipData->code.'|unique:users,phone,'.auth()->user()->id.',id',
            'email' => 'required|string|max:180|email|unique:users,email,'.auth()->user()->id.',id',
            'name' => 'required|string|min:3|max:180',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.numeric'=> 'Please enter valid Phone Number',
            'phone.unique' => 'The given phone number already exist',
            'phone.phone' => 'Please enter valid Phone Number',
            'email.unique' => 'The given email already exist',
            'email.required' => 'Email id is required',
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name must be less than 30 characters.',
        ]);
        if(empty($this->verified_status)){
            $this->verified_status = 'Please verify phone number.';
        }
        if($this->verified_status=='verified'){
            User::where('id',auth()->user()->id)->update($validatedData);
            $this->message = 'success';
            $this->is_edit = false;
        }
    }
    public function EditEnable(){
        $this->is_edit = true;
    }
    public function mount(){
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
        $this->phone = auth()->user()->phone;
        $this->verified_phone_number = $this->phone;

    }
    public function render()
    {
        return view('livewire.ecommerce.user.auth.account-setting');
    }
}
