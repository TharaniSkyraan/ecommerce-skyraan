<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Mail\RestockMail;
use App\Models\NofityAvailableProduct;
use App\Models\ProductVariant;
use App\Traits\ZoneConfig;

class NotifyAvailableProduct extends Command
{
    use ZoneConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyavailableproduct:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Available product description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $notifications = NofityAvailableProduct::whereAttempts(0)->get()
                                                ->each(function ($items) {
                                                    $items->append(['address']);
                                                    return $items;
                                                })->toArray();
                
        $notifications = array_filter($notifications, function($notification) {
            return $notification['address'] !== null;
        });
        foreach($notifications as $nofi)
        {
            $address = $nofi['address'];
            $data = array(
                'address_id' => $address['id'],
                'city' => $address['city'], 
                'latitude' => '', 
                'longitude' => '', 
                'postal_code' => $address['postal_code']
            );  

            $result = $this->configzone($data); 

            $warehouse_ids = array_filter(explode(',',$result['warehouse_ids']));
            if(count($warehouse_ids)!=0)
            {

                $productVariant = ProductVariant::with('product')->whereHas('product', function($q){
                    $q->where('status','active');
                })->whereHas('product_stock', function($q1) use($warehouse_ids){
                    $q1->whereIn('warehouse_id', $warehouse_ids)
                    ->where('stock_status', 'in_stock');
                })->first();
                
                $images = json_decode($productVariant->images, true);
                $images = (count($images)!=0)?$images:json_decode($productVariant->product->images, true);
                    
                $data['name'] = $productVariant->product_name;
                $data['image'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                $data['link'] =  route('ecommerce.product.detail', ['slug' => $productVariant->product->slug])."?prdRef=".\Carbon\Carbon::parse($productVariant->product->created_at)->timestamp."&product_variant=".$productVariant->id;
                if(isset($productVariant)){
                    \Mail::send(new RestockMail($data,$nofi['user']['name'],$nofi['user']['email']));
                }
                
            }         
            
        }

    }

}
