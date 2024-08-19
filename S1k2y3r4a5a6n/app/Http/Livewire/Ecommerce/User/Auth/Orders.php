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
use App\Models\ProductStock;
use App\Models\StockHistory;
use App\Models\ShippingHistory;
use App\Models\ProductStockUpdateQuantityHistory;
use Razorpay\Api\Api;
use App\Traits\OrderInvoice;
use App\Mail\OrderCancelMail;
use App\Mail\RefundRequestedMail;
use App\Mail\RefundInitiated;
use App\Mail\RefundCancelMail;

class Orders extends Component
{
    use WithPagination, OrderInvoice;
    public $tab='all';
    public $pageloading = 'false';
    public $morepage = false;
    public $cancelorderLoader = true;
    public $page = 1;
    public $orders = [];
    public $reasons = [];
    public $dates = [];
    public $total_orders,$reason,$notes,$order_id,$order_code;
    public $isopenmodel;
    public $sort_by = 'last_30_days';

    protected $queryString = ['tab','sort_by'];

    protected $listeners = ['loadMore'];
    
    protected $rules = [
        'reason' => 'required|string',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'reason.required' => 'Please select a reason for cancellation.',
    ];

    public function cancelOrderRequest($ordRef)
    {
        $this->reasons = CancelReason::get()->pluck('name')->toArray();
        $this->order_id = Order::whereCode($ordRef)->pluck('id')->first();
        $this->order_code = $ordRef;
        $this->isopenmodel = 'show';
        $this->cancelorderLoader = false;
    }
    public function cancelOrder()
    {
        $validatedData =  $this->validate([
            'order_id'=>'required',
            'reason'=>'required',
            'notes'=>'nullable|string|min:3|max:255',
        ]);
        $this->cancelorderLoader = true;
        
        $order = Order::find($this->order_id);
        
        CancelOrder::updateOrCreate(
            ['order_id' => $this->order_id],
            $validatedData
        );

        Order::where('id',$this->order_id)->update(['status'=>'cancelled']);

        $stockhistories = StockHistory::whereReferenceNumber($this->order_id)
                                      ->whereStockType('order')->get();
        
        foreach($stockhistories as $stock_history)
        {

            $stock_history =  StockHistory::find($stock_history->id)->update(['received_date' => Carbon::now(),'status' => 'cancelled']);

            $quantity_update_histories = ProductStockUpdateQuantityHistory::whereHistoryId($stock_history->id)->get();

            foreach($quantity_update_histories as $quantity_history)
            {

                $product_stock = ProductStock::whereWarehouseId($quantity_history->warehouse_id)
                                              ->whereProductVariantId($quantity_history->product_variant_id)
                                              ->first();

                $product_stock = ProductStock::find($product_stock->id);
                $available_stock = $product_stock->available_quantity;
                $product_stock->available_quantity += $quantity_history->updated_quantity;
                $product_stock->stock_status = ($product_stock->available_quantity<1)?'out_of_stock':'in_stock';
                $product_stock->save();
                
                ProductStockUpdateQuantityHistory::create([
                    'history_id' => $stock_history->id,
                    'warehouse_id' => $quantity_history->warehouse_id,
                    'product_name' => $quantity_history->product_name,
                    'product_id' => $quantity_history->product_id,
                    'product_variant_id' => $quantity_history->product_variant_id,
                    'previous_available_quantity' => $available_stock,
                    'updated_quantity' => $quantity_history->updated_quantity,
                    'available_quantity' => $available_stock + $quantity_history->updated_quantity,
                ]);

            }

        }

        OrderShipment::where('order_id',$this->order_id)->update(['status'=>'cancelled']);
        OrderHistory::updateOrCreate(['order_id'=>$this->order_id,'action'=>'cancelled'],['description'=>'You requested a cancellation because '.$this->reason.'.']);
        ShippingHistory::updateOrCreate(['order_id'=>$this->order_id,'user_id'=>$order->user_id,'action'=>'order_cancelled','shipment_id'=>$order->shipment->id??''],['description'=>'You requested a cancellation because '.$this->reason.'.']);

        // Order cancelled mail
        $order= Order::find($this->order_id);
        \Mail::send(new OrderCancelMail($order));

        if(!empty($order->payments->charge_id)){
            try {
                $amount = $order->payments->amount;
                $api = new Api(config('shipping.razorpay.razorpay_key'), config('shipping.razorpay.razorpay_secret'));
                $refund = $api->payment->fetch($order->payments->charge_id)->refund([
                    'amount' => $amount ? $amount * 100 : null  // Amount in paise
                ]);
                // \Log::info('Refund successful: ' . $refund['id']);
                // Refund request mail
                \Mail::send(new RefundRequestedMail($order));
                // Refund initiate mail
                \Mail::send(new RefundInitiated($order));

            } catch (\Exception $e) {
                // \Log::info('Refund failed: ' . $e->getMessage());
                // Refund request mail
                \Mail::send(new RefundRequestedMail($order));
                // Refund cancelled mail
                \Mail::send(new RefundCancelMail($order));

            }
        }
        
        $this->reset(['order_id','reason', 'notes']);  

        $this->isopenmodel = '';

        $this->emit('OrderCancelSuccessfully',$this->order_code);
    }

    public function invoiceGenerate($order_id)
    {
        return $this->generateinvoice($order_id, 'download');
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
            $orders = Order::where('user_id',auth()->user()->id);
            if($this->sort_by=='last_30_days'){
                $thirtyDaysAgo = Carbon::now()->subDays(30);
                $orders->where('created_at', '>=', $thirtyDaysAgo);
            }elseif($this->sort_by=='past_3_months'){
                $threeMonthsAgo = Carbon::now()->subMonths(3);
                $orders->where('created_at', '>=', $threeMonthsAgo);
            }elseif(!empty($this->sort_by)){
                $startOfYear = Carbon::create($this->sort_by, 1, 1);
                $endOfYear = Carbon::create($this->sort_by, 12, 31, 23, 59, 59);                
                $orders->whereBetween('created_at', [$startOfYear, $endOfYear]);
            }
            $orders->orderBy('id','desc');
        }if($this->tab=='shipped'){
            $orders = Order::where('user_id',auth()->user()->id)
                            ->whereIn('status',['shipped','out_for_delivery'])
                            ->orderBy('id','desc');
        }if($this->tab=='cancelled'){
            $orders = Order::where('user_id',auth()->user()->id)
                            ->where('status','cancelled')
                            ->orderBy('id','desc');
        }
        if($this->tab!='buy-again'){
            $orders = $orders->whereHas('shipmentAddress', function($q){
                $q->where('user_id','!=','');
             })->paginate(20, ['*'], 'page', $this->page);
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
            
            $orders = OrderItem::whereHas('orders', function($q1){
                                    $q1->where('status','delivered');
                                })->whereHas('product', function($q1){
                                    $q1->where('status','active');
                                })->select('product_id', 'attribute_set_ids')
                                ->groupBy('product_id', 'attribute_set_ids')
                                ->paginate(20, ['*'], 'page', $this->page);
                        
            $this->total_orders = $orders->total();
            $this->morepage = $orders->hasMorePages(); 

            $orders = $orders->each(function ($order, $key) {                                  
                            $attribute_ids = array_filter(explode(',',$order->attribute_set_ids));
                            $variant = ProductVariant::select('id','product_name','price','images','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','stock_status')
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
                                            $discount = (($price-$sale_price)/$price)*100;
                                        } 
                
                                    }else{
                                        $sale_price = $variant->sale_price;
                                        $discount = (($price-$sale_price)/$price)*100;
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
                            $product['product_name'] = $variant->product_name??$product['name'];
                            $product['attributes'] = implode('| ',$attributes);
                            $product['variant_id'] = $variant->id??0;
                            $product['sale_price'] = $sale_price;
                            $product['discount'] = ($discount!=0)?(round($discount)):0;
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

    public function sortByUpdate($sort_by){
        $this->sort_by = $sort_by;
        $this->filterOrders();
    }

    public function mount()
    {
        $this->filterOrders();
        $this->dates = OrderItem::select(\DB::raw('YEAR(created_at) as year'))->groupBy('year')->orderBy('year','desc')->get()->toArray();   
    }

    public function render()
    {
        return view('livewire.ecommerce.user.auth.orders');
    }

}
