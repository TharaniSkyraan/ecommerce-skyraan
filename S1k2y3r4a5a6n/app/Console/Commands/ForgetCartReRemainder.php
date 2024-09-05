<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Mail\ForgetCartMail;
use App\Models\ProductVariant;

class ForgetCartReRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:forget-cart-reremiander';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remainder for buy the cart product';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = \Carbon\Carbon::now()->subDays(15);
        $carts = Cart::whereHas('user', function($q){
                $q->whereSubscription('enabled');
            })->whereNull('last_reminder_date')
            ->where('attempt', 1)
            // ->whereDate('updated_at', '<=', $date)
            ->groupBy('user_id')
            ->pluck('user_id')->toArray();
    
        foreach ($carts as $user_id) {
            $cart_products = Cart::where('user_id', $user_id)->get()->map(function ($products) {
                $variant = ProductVariant::find($products->product_variant_id);
                if ($variant) {
                    $products->name = $variant->product_name;
                    $products->price = $variant->search_price;
                    $images = json_decode($variant->images, true);
                    if (empty($images) && $variant->product) {
                        $images = json_decode($variant->product->images, true);
                    }
                    $products->image = isset($images[0]) ? asset('storage/' . $images[0]) : asset('asset/home/default-hover1.png');
                }
                return $products;
            });
    
            $user = User::find($user_id);
    
            if ($user && $cart_products->isNotEmpty()) {
                Cart::whereUserId($user_id)->update(['last_reminder_date'=>\Carbon\Carbon::now(),'attempt'=>2]);
                \Mail::send(new ForgetCartMail($cart_products, $user->name, $user->email));
            }
        }
    }
}
