<?php

namespace App\Http\Livewire\Ecommerce\User;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Carbon\Carbon;
use App\Mail\ResetPassword;

class ForgotPassword extends Component
{
    public $email;
    public $message;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users',
        ]);
        
        $token = \Str::random(10);

        \DB::table('password_reset_tokens')->where(['email'=> $this->email])->delete();
        \DB::table('password_reset_tokens')->insert(['email' => $this->email, 'token' => $token, 'created_at' => Carbon::now()]);
        
        // Send Mail to ResetPasswordLink
        \Mail::send(new ResetPassword($this->email,encrypt($token, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213')));
        $this->emit('showToast', 'Password reset link sent! Please check your email');
        $this->reset(['email']); 
     
    }
    
    public function render()
    {
        return view('livewire.ecommerce.user.forgot-password');
    }
}