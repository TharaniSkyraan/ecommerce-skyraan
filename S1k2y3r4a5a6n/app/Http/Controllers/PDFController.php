<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Mail\ResetPassword;
use App\Mail\ForgetCart;
use App\Models\Cart;
use App\Models\User;
use App\Models\ProductVariant;

class PDFController extends Controller
{
    public function testingfun()
    {
        $date = \Carbon\Carbon::now()->subDays(15);
        $carts = Cart::whereNull('last_reminder_date')
            ->where('attempt', 0)
            ->whereDate('updated_at', '<=', $date)
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
                \Mail::send(new ForgetCart($cart_products, $user->name, $user->email));
            }
        }
    
        return $carts;
    }
    
    public function generatePDF()
    {
        $order = Order::find(7);
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();

        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = $imagePath;
        // $data = [
        //     'title' => 'My PDF Title',
        //     'content' => 'This is the content of the PDF.',
        // ];

        return view('ecommerce.order.invoice',$data);
        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);

        return $pdf->download('document.pdf');
    }
}
