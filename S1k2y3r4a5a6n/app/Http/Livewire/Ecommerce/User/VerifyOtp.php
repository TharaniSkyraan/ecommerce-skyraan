<?php

namespace App\Http\Livewire\Ecommerce\User;

use Livewire\Component;
use App\Models\TempPhone;
use Carbon\Carbon;

class VerifyOtp extends Component
{
    public $phone,$session_otp,$session,$error,$otp,$verified_status,$remaining_time;

    protected $listeners = ['SendOtp'];

    public function updatedOtp()
    {
        $this->error = '';        
        $otp = $this->otp;
        $startdate = Carbon::parse($this->session_otp);
        $enddate = Carbon::now();
        $check_otp = decrypt($this->session, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
        if((($startdate->diffInSeconds($enddate)) > 300))  // 5 refers to 5 minutes
        {
            $this->error = 'OTP expired. Please try again.';
        }elseif($otp!=$check_otp){
            $this->error = 'Invalid OTP. Please try again.';
        }else{
            $this->verified_status = 'success';
            $this->emit('PhoneNumberVerified');
        }
    }

    public function ReSendOtp(){
        $this->SendOtp($this->phone);
    }

    public function SendOtp($phone)
    {
        $this->phone = $phone;
        $user = TempPhone::wherePhone($phone)->first();
        $startdate = (isset($user))?Carbon::parse($user->session_otp):Carbon::now();
        $enddate = Carbon::now();

        if((($startdate->diffInSeconds($enddate)) > 300) || !isset($user))  // 5 refers to 5 minutes
        {
            // Generate a random 6-digit OTP
            $otp = mt_rand(100000, 999999);

            if(config('services.sms_gateway')=='twilio')
            {
                // $otp = '123456';
                // Twilio Account SID and Auth Token
                $account_sid = config('services.twilio.twilio_account_sid');
                $auth_token = config('services.twilio.twilio_auth_token');

                // Twilio phone number
                $twilio_number = config('services.twilio.twilio_number'); // Replace with your Twilio phone number

                // Recipient's phone number
                $to_number = '+91'.$phone; // Replace with the recipient's phone number

                // Twilio SMS API URL
                $url = "https://api.twilio.com/2010-04-01/Accounts/{$account_sid}/Messages.json";

                // // Build the cURL request
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'To' => $to_number,
                    'From' => $twilio_number,
                    'Body' => "Your OTP is: {$otp}"
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "{$account_sid}:{$auth_token}");

                // Execute the cURL request
                $response = curl_exec($ch);
                curl_close($ch);

                // Check for errors and handle the response
                // if (!$response) {
                //    // \Log::info("Failed to send OTP.");
                // } else {
                //    // \Log::info("OTP sent successfully!");
                // }
            }
           
            $this->session_otp = Carbon::now();  
            $this->session = encrypt($otp, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');  
            $data['phone'] = $phone;
            $data['otp'] = $otp;
            $data['session_otp'] = $this->session_otp;  
            $this->remaining_time = 300;
            TempPhone::updateOrCreate(['phone' => $phone],$data);
        }else{
            $this->session_otp = Carbon::parse($user->session_otp);
            $this->session = encrypt($user->otp, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');
            $this->remaining_time = 300 - $startdate->diffInSeconds($enddate);
        }           
        
        $this->emit('CalculateTimer',$this->remaining_time);
          
    }
    
    public function render()
    {
        return view('livewire.ecommerce.user.verify-otp');
    }
}
