<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\PasswordChangedMail;

class ResetPasswordController extends Controller
{
    protected function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required',
        ]);

        $token = decrypt($request->token, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213');

        $updatePassword = \DB::table('password_reset_tokens')
                            ->where(['email' => $request->email, 'token' =>$token])
                            ->first();

        if(!$updatePassword)
            return back()->withInput()->with('error', 'Invalid token!');
        
        $user = User::where('email', $request->email)->first();
        $name = $user->name;
        $email = $user->email;
        $user->password = bcrypt($request->password);
        $user->save();

        \DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        \Mail::send(new PasswordChangedMail($name,$email));

        // Log the user in after password reset
        \Auth::login($user);

        return redirect(route('ecommerce.home'));
    }
}
