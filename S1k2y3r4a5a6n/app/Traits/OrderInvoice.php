<?php

namespace App\Traits;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

trait OrderInvoice
{

    public function generateinvoice($order_id, $action='')
    {
        $order = Order::find($order_id);
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();
        $data['order']['order_date'] = $order->created_at->copy()->timezone('Asia/Kolkata');
        $data['order']['invoiced_date'] = \Carbon\Carbon::parse($order->invoice_date)->copy()->timezone('Asia/Kolkata');
        $data['order']['prininword'] = ucwords($this->numToWordsRec($data['order']['total_amount']));
    
        // Encode logo as base64
        $logoImagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $logoImageData = base64_encode(file_get_contents($logoImagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $logoImageData;
    
        // Encode mail icon as base64
        $mailIconPath = public_path('asset/home/invoice-mail.png');
        $mailIconData = base64_encode(file_get_contents($mailIconPath));
        $data['mail_icon_base64'] = 'data:image/png;base64,' . $mailIconData;
    
        // Encode phone icon as base64
        $phoneIconPath = public_path('asset/home/invoice-phone.png');
        $phoneIconData = base64_encode(file_get_contents($phoneIconPath));
        $data['phone_icon_base64'] = 'data:image/png;base64,' . $phoneIconData;
    
        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
    
        if ($action == 'download') {
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $order->invoice_number . '.pdf');
        } elseif ($action == 'preview') {
            $pdfBase64 = base64_encode($pdf->output());
            $this->emit('previewInvoice', $pdfBase64);
        } else {
            $this->pdfBase64 = base64_encode($pdf->output());
        }
    }
    
    
    function numToWordsRec($number) {
        $words = array(
            0 => 'zero', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five',
            6 => 'six', 7 => 'seven', 8 => 'eight',
            9 => 'nine', 10 => 'ten', 11 => 'eleven',
            12 => 'twelve', 13 => 'thirteen', 
            14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty',
            90 => 'ninety'
        );

        if ($number < 20) {
            return $words[$number];
        }

        if ($number < 100) {
            return $words[10 * floor($number / 10)] .
                ' ' . $words[$number % 10];
        }

        if ($number < 1000) {
            return $words[floor($number / 100)] . ' hundred ' 
                . $this->numToWordsRec($number % 100);
        }

        if ($number < 1000000) {
            return $this->numToWordsRec(floor($number / 1000)) .
                ' thousand ' . $this->numToWordsRec($number % 1000);
        }

        return $this->numToWordsRec(floor($number / 1000000)) .
            ' million ' . $this->numToWordsRec($number % 1000000);
    }

}