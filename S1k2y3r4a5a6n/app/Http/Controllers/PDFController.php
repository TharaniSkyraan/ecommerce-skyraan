<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class PDFController extends Controller
{
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
