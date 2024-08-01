<?php

use Illuminate\Http\Request;
use App\Mail\WelcomeMail;
use App\Models\User;
Route::get('/email_template', function () {
    $user = User::first();
    \Mail::send(new WelcomeMail('pavithra', 'tharani@skyraan.com'));
    return 'success';
});
use App\Http\Controllers\PDFController;

Route::get('/privacy-policy', function () {
    return view('ecommerce/privacy');
});

Route::get('/terms-and-condition', function () {
    return view('ecommerce/terms');
});

Route::get('/generate-pdf', [PDFController::class, 'generatePDF']);

Route::post('reset-password','App\Http\Controllers\Auth\ResetPasswordController@resetPassword')->name('password.update');

Route::name('ecommerce.')->group(function () {

    Route::group(['namespace'=>'App\Http\Controllers\Ecommerce'],function(){
    
        Route::get('/reset-password/{token}', function (Request $request, $token) {
            $email = $request->email??'';
            return view('ecommerce/user/reset-password',compact('email','token'));
        })->name('reset.password');

        Route::get('/', function () { return view('ecommerce/home'); })->name('home');
       
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified'
        ])->group(function () {

            Route::get('/dashboard', function () { return view('ecommerce/user/auth/dashboard'); })->name('dashboard');

            Route::get('/account', function () { return view('ecommerce/user/auth/account-setting'); })->name('account');

            Route::get('/address-list', function () { return view('ecommerce/user/auth/address-list'); })->name('address-list');

            Route::get('/wish-lists', function () { return view('ecommerce/user/auth/wish-lists'); })->name('wish-lists');
            
            Route::get('/orders', function () { return view('ecommerce/user/auth/orders'); })->name('orders');

            Route::get('/order-detail', function () { return view('ecommerce/user/auth/order-detail'); })->name('order-detail');

            Route::get('/invoice', function () { return view('ecommerce/user/auth/invoice'); })->name('invoice');
        
            Route::get('/cart', function () { return view('ecommerce/product/cart'); })->name('cart');

            Route::get('/checkout', function () {
                if(count(auth()->user()->cart)==0){
                    return redirect('/');
                }
                return view('ecommerce/product/checkout'); 
            })->name('checkout');
     
            Route::get('/order-placed/{code?}', function ($code) { return view('ecommerce/product/order-placed', compact('code')); })->name('order-placed');

        });
        
        Route::get('/aboutus', function () { return view('ecommerce/aboutus'); })->name('aboutus');

        Route::get('/contactus', function () { return view('ecommerce/contactus'); })->name('contactus');

        Route::get('/product/{slug}', function (Request $request, $slug) { 
            try {
                
                // $decrypted = ($request->product_variant)?decrypt($request->product_variant, 'aes-256-cbc', 'SkyRaan213', 0, 'SkyRaan213'):'';
                return view('ecommerce/product/detail',compact('slug'));
            } catch (Exception $e) {
                // Handle decryption errors
                return abort(404);
            }
         })->name('product.detail');

        // Product collection type or product name filter
        Route::get('{type}/{slug?}',function($type,$slug=''){
            return view('ecommerce.product.search-result',compact(['slug','type']));
        })->name('product.list');

    });

});
