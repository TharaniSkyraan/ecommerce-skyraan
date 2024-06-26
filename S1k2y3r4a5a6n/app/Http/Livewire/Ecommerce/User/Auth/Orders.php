<?php

namespace App\Http\Livewire\Ecommerce\User\Auth;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\AttributeSet;
use App\Models\ProductVariant;
use App\Models\Tax;
use App\Models\CancelOrder;
use App\Models\CancelReason;
use App\Models\OrderShipment;
use App\Models\OrderHistory;
use App\Models\ShippingHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Razorpay\Api\Api;

class Orders extends Component
{
    use WithPagination;
    public $tab='all';
    public $pageloading = 'false';
    public $morepage = false;
    public $page = 1;
    public $orders = [];
    public $reasons = [];
    public $total_orders,$reason,$notes,$order_id,$order_code;
    public $isopenmodel;

    protected $queryString = ['tab'];

    protected $listeners = ['loadMore'];

    public function cancelOrderRequest($ordRef)
    {
        $this->reasons = CancelReason::get()->pluck('name')->toArray();
        $this->order_id = Order::whereCode($ordRef)->pluck('id')->first();
        $this->order_code = $ordRef;
        $this->isopenmodel = 'show';
        $this->emit('OpenCancelRequestModel');
    }
    
    public function cancelOrder()
    {

        $validatedData =  $this->validate([
            'order_id'=>'required',
            'reason'=>'required',
            'notes'=>'required|string|min:3|max:255',
        ]);
        $order = Order::find($this->order_id);
        
        if(!empty($order->payments->charge_id)){
            try {
                $amount = $order->payments->amount;
                $api = new Api(config('shipping.razorpay.razorpay_key'), config('shipping.razorpay.razorpay_secret'));
                $refund = $api->payment->fetch($order->payments->charge_id)->refund([
                    'amount' => $amount ? $amount * 100 : null  // Amount in paise
                ]);
                // \Log::info('Refund successful: ' . $refund['id']);
            } catch (\Exception $e) {
                // \Log::info('Refund failed: ' . $e->getMessage());
            }
        }
        
        CancelOrder::updateOrCreate(
            ['order_id' => $this->order_id],
            $validatedData
        );
        Order::where('id',$this->order_id)->update(['status'=>'cancelled']);
        OrderShipment::where('order_id',$this->order_id)->update(['status'=>'cancelled']);
        OrderHistory::updateOrCreate(['order_id'=>$this->order_id,'action'=>'cancelled'],['description'=>'You requested a cancellation because '.$this->reason.'.']);
        ShippingHistory::updateOrCreate(['order_id'=>$this->order_id,'user_id'=>$order->user_id,'action'=>'order_cancelled','shipment_id'=>$order->shipment->id??''],['description'=>'You requested a cancellation because '.$this->reason.'.']);
        $this->reset(['order_id','reason', 'notes']);  
        
        $this->isopenmodel = '';
        $this->emit('OrderCancelSuccessfully',$this->order_code);
    }

    public function invoiceGenerate($order_id){
        
        $order = Order::find($order_id);
        $data['shipment_address'] = $order->shipmentAddress->toArray();
        $data['shipment'] = $order->shipment->toArray();
        $data['order_items'] = $order->orderItems->toArray();
        $data['order'] = $order->toArray();
        $imagePath = 'https://skyraa-ecommerce.skyraan.net/storage/setting/eB7sQkTnA7rdrXOxAAiPKYGt82C0QUABpeJc2yaB.svg';
        $imageData = base64_encode(file_get_contents($imagePath));
        $data['logo_base64'] = 'data:image/png;base64,' . $imageData;
        $pdf = Pdf::loadView('ecommerce.order.invoice', $data);
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $order->invoice_number.'.pdf');
        // return $pdf->download('document.pdf');

    }
    
    public function loadMore()
    {
        $this->filterOrders();
    }

    public function updatedTab(){
       $this->page = 1;
       $this->filterOrders();
    }

    public function filterOrders()
    {
        if($this->tab=='all'){
            $orders = Order::where('user_id',auth()->user()->id)->orderBy('created_at','desc');
        }if($this->tab=='shipped'){
            $orders = Order::where('user_id',auth()->user()->id)
                            ->whereIn('status',['shipped','out_for_delivery'])
                            ->orderBy('created_at','desc');
        }if($this->tab=='cancelled'){
            $orders = Order::where('user_id',auth()->user()->id)
                            ->where('status','cancelled')
                            ->orderBy('created_at','desc');
        }
        if($this->tab!='buy-again'){
            $orders = $orders->paginate(2, ['*'], 'page', $this->page);
            $this->total_orders = $orders->total();
            $this->morepage = $orders->hasMorePages();       
            $orders = $orders->each(function ($order, $key) {            
                                $order['shipment_address'] = $order->shipmentAddress;
                                $order['shipment'] = $order->shipment;
                                $order['order_status'] = OrderHistory::whereOrderId($order->id)->pluck('description')->first();
                                $order['order_items'] = $order->orderItems->each(function ($orderItem) {
                                    $orderItem['variant'] = $orderItem->variant;
                                    $orderItem['product'] = $orderItem->product;
                                    $orderItem['review'] = $orderItem->review;
                                    return $orderItem;
                                });
                                return $order;
                            })->toArray();
            if($this->page!=1){
                $this->orders = array_merge($this->orders, $orders);
            }else{
                $this->orders = $orders;
            }
            $this->pageloading = 'false';
        }else
        {
            $orders = OrderItem::whereHas('orders', function($q){
                            $q->where('user_id',auth()->user()->id)
                              ->where('status','delivered');
                        })
                        ->select('product_id', 'attribute_set_ids')
                        ->groupBy('product_id', 'attribute_set_ids')
                        ->paginate(2, ['*'], 'page', $this->page);
                        
            $this->total_orders = $orders->total();
            $this->morepage = $orders->hasMorePages(); 

            $orders = $orders->each(function ($order, $key) {                                  
                            $attribute_ids = array_filter(explode(',',$order->attribute_set_ids));
                            $variant = ProductVariant::select('id','price','images','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
                                                    ->where(function($q) use($attribute_ids) {
                                                        foreach($attribute_ids as $set_id){
                                                            $q->whereHas('product_attribute_set', function($q1) use($set_id){
                                                                $q1->whereAttributeSetId($set_id);
                                                            });
                                                        }
                                                    })->whereProductId($order->product_id)->first();
                            $product = Product::where('id',$order->product_id)->select('id','slug','images','name','tax_ids','created_at')->first()->toArray();
                           
                            $discount = $price = $sale_price = 0;
                            if(isset($variant))
                            {
                                $price = $variant->price;                
                
                                if($variant->sale_price!=0 && $variant->discount_expired!='yes'){
                                    
                                    if($variant->discount_duration=='yes'){
                
                                        $currentDate = Carbon::now()->format('d-m-Y H:i');
                
                                        // Start and end date from user input or database
                                        $startDate = Carbon::parse($variant->discount_start_date)->format('d-m-Y H:i'); 
                                        $endDate = Carbon::parse($variant->discount_end_date)->format('d-m-Y H:i'); 
                
                                        // Validate start date
                                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                                            $sale_price = $variant->sale_price;
                                            $discount = ($sale_price/$price)*100;
                                        } 
                
                                    }else{
                                        $sale_price = $variant->sale_price;
                                        $discount = ($sale_price/$price)*100;
                                    }
                                    
                                }
                                
                                if(!empty($product['tax_ids']))
                                {
                                    if($tax = Tax::where('id',$product['tax_ids'])->where('status','active')->first())
                                    {                    
                                        $price = $price + ($tax->percentage * ($price / 100));
                                        if($sale_price!=0){
                                            $sale_price = $sale_price + ($tax->percentage * ($sale_price / 100));
                                        }  
                                    }
                                }
                
                            }
                            $images = json_decode($variant->images, true);
                            if(count($images)==0){
                                $images = json_decode($product['images'], true);
                            }
                            
                            $attributes = AttributeSet::where('id',$attribute_ids)->pluck('name')->toArray();
                            $product['image1'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                            $product['image2'] = (isset($images[1]))?asset('storage').'/'.$images[1]:asset('asset/home/default-hover1.png');
                            $product['stock_status'] = $variant->stock_status;
                            $product['slug'] = $product['slug'];
                            $product['price'] = $price;
                            $product['attributes'] = implode('| ',$attributes);
                            $product['variant_id'] = $variant->id??0;
                            $product['sale_price'] = $sale_price;
                            $product['discount'] = ($discount!=0)?(100 - round($discount)):0;
                            $product['last_buy'] = OrderItem::whereHas('orders', function($q){
                                                                $q->whereStatus('delivered');
                                                            })->whereProductId($order->product_id)
                                                            ->whereAttributeSetIds($order->attribute_set_ids)
                                                            ->orderBy('created_at','desc')
                                                            ->pluck('created_at')
                                                            ->first();
                            $order['product'] = $product;

                            return $order;
                        })->toArray();
            if($this->page!=1){
                $this->orders = array_merge($this->orders, $orders);
            }else{
                $this->orders = $orders;
            }
            $this->pageloading = 'false';  
        }
    }

    public function mount()
    {
        $this->filterOrders();
    }

    public function render()
    {
        return view('livewire.ecommerce.user.auth.orders');
    }

}
