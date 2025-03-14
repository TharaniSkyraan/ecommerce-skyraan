<?php

use Illuminate\Support\Facades\Route;

use App\Mail\WelcomeMail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/sendMail', function(){
    \Mail::send(new WelcomeMail('Tharani', 'tharani@skyraan.com'));
    // \Mail::send(new ForgetCartMail($cart_products, $user->name, $user->email));

});

// Route::get('/view-invoice', function () {
//     return view('e-commerce/view-invoice');
// });

// Ecommerce Admin routes
// By Tharani

$real_path = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'web_routes' . DIRECTORY_SEPARATOR;

include_once($real_path . 'admin.php');

// Cache Clear
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

// Ecommerce Website Dynamic routes
// By Tharani

include_once($real_path . 'website.php');
