<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\SavedAddress;
use App\Models\UserCart;
use App\Models\Cart as CartItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\ProductAttributeSet;
use App\Models\AttributeSet;
use App\Models\Collection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipment;
use App\Models\OrderPayment;
use App\Models\Zone;
use App\Models\Setting;
use App\Models\Tax;
use Carbon\Carbon;
use Razorpay\Api\Api;

class Checkout extends Component
{
    public $address_id,$coupon_code,$coupon_error,$apply_for,$payment_id,$place_order,$zone,$lat,$lng;
    public $payment_method = 'cash';
    public $summary_show = false;
    public $addresslist = false;
    
    public $total_price = 0;
    public $coupon_discount = 0;
    public $shipping_charges = 0;

    protected $listeners = ['colapseSummaryShow','colapseAddressList','addressList','completeOrder','CouponApplied'];

    protected $queryString = ['payment_id'];

    public function colapseSummaryShow()
    {
        if($this->summary_show==false){
            $this->summary_show = true;
        }else{
            $this->summary_show = false;
        }
    }

    public function colapseAddressList()
    {
        if($this->addresslist==false){
            $this->addresslist = true;
        }else{
            $this->addresslist = false;
        }
    }

    public function updatedAddressId()
    {
        $address = SavedAddress::find($this->address_id);
        $validateData['user_address_id'] = $this->address_id;
        $validateData['postal_code'] = $address->zip_code;
        UserCart::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $validateData
        );
        $this->pointInPolygon($this->address_id);
        $this->calculateShippingCharges();
    }

    public function edit($id='')
    {
        $this->emit('editAddress',$id);
    }

    public function addressList()
    {
        $this->addresses = SavedAddress::whereUserId(auth()->user()->id)->get()->toArray();
        $this->pointInPolygon($this->address_id);
        $this->calculateShippingCharges();
    }
    
    public function cartList()
    {
        $datas = CartItem::whereUserId(auth()->user()->id)->get()->toArray();

        $cart_products = [];
        $total_price = 0;

        foreach($datas as $data)
        {
            if(Product::where('id',$data['product_id'])->exists() && ProductVariant::where('id',$data['product_variant_id'])->exists()){
                
                $product = Product::where('id',$data['product_id'])->select('id','slug','name','images','tax_ids')->first()->toArray();
                $default = ProductVariant::where('id',$data['product_variant_id'])->select('price','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration','shipping_weight as weight')->first();

                $discount = $price = $sale_price = 0;
                
                if(isset($default))
                {

                    $attribute_set_ids = ProductAttributeSet::whereProductVariantId($data['product_variant_id'])->pluck('attribute_set_id')->toArray();
                    $attribute_set_name = AttributeSet::find($attribute_set_ids)->pluck('name')->toArray();
                    
                    $price = $default->price;

                    if($default->sale_price!=0 && $default->discount_expired!='yes')
                    {
                        if($default->discount_duration=='yes'){

                            $currentDate = Carbon::now()->format('d-m-Y H:i');

                            // Start and end date from user input or database
                            $startDate = Carbon::parse($default->discount_start_date)->format('d-m-Y H:i'); 
                            $endDate = Carbon::parse($default->discount_end_date)->format('d-m-Y H:i'); 

                            // Validate start date
                            if ($startDate <= $currentDate && $currentDate <= $endDate) {
                                $sale_price = $default->sale_price;
                                $discount = (($price-$sale_price)/$price)*100;
                            } 

                        }else{
                            $sale_price = $default->sale_price;
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

                    $images = json_decode($product['images'], true);
                    $product['cart_id'] =  $data['id'];
                    $product['image'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                    $product['variant_id'] = $data['product_variant_id'];
                    $product['slug'] = $product['slug'];
                    $product['price'] = $price;
                    $product['weight'] = $default->weight*$data['quantity'];
                    $product['shipping_gross_amount'] = 0;
                    $product['shipping_tax'] = 0;
                    $product['sale_price'] = $sale_price;
                    $product['discount'] = ($discount!=0)?(round($discount)):0;
                    $product['quantity'] = $data['quantity'];
                    $product['attributes'] = implode(', ',$attribute_set_name);
                    $product['total_price'] = (($discount!=0)?($data['quantity']*$sale_price):($data['quantity']*$price));
                    $total_price += $product['total_price'];
                    $product['product_type'] = ProductVariant::whereProductId($data['product_id'])->count();
                
                    $cart_products[$data['id']] = $product;
                            
                }

            }else{
                CartItem::where('id',$data['id'])->delete(); 
            }
           
        }
        $this->total_price = $total_price;
        $this->cart_products = $cart_products;
    }

    public function calculateShippingCharges()
    {
        $cart_products = $this->cart_products; 
        
        $setting = Setting::first();
        $minimum_kg = $setting->minimum_kg;
        $cost_per_kg = $setting->cost_per_kg;
        $cost_minimum_kg = $setting->cost_minimum_kg;
        $minimum_km = $setting->minimum_km;
        $cost_per_km = $setting->cost_per_km;
        $cost_minimum_km = $setting->cost_minimum_km;

        if($zone = Zone::find($this->zone))
        {
            $latitudeFrom = $zone->lat;
            $longitudeFrom = $zone->lng;
            $latitudeTo = $this->lat;
            $longitudeTo = $this->lng;
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$latitudeFrom,$longitudeFrom&destinations=$latitudeTo,$longitudeTo&key=".config('shipping.google_map_api_key');
            $details=file_get_contents($url);
            $result = json_decode($details,true);

            $distance = $result['rows'][0]['elements'][0]['distance']['value']??0;
            $distance = round($distance/1000, 2);
        }
        $shipping_charges = 0;
        foreach($cart_products as $key => $cart_product)
        {
            $weight = $cart_product['weight'];
            $shipping_charge = 0;
            if(!empty($this->zone) && ($setting->is_enabled_shipping_charges=='yes'))
            {

                if($weight<=$minimum_kg){
                    $shipping_charge = $cost_minimum_kg;
                }else{
                    $shipping_charge = $cost_minimum_kg + (($weight-$minimum_kg)*$cost_per_kg);
                }
                
                if($distance<=$minimum_km){
                    $shipping_charge += $cost_minimum_km;
                }else{
                    $shipping_charge += $cost_minimum_km + (($distance-$minimum_km)*$cost_per_km);
                }
                
                if($setting->is_enabled_shipping_tax && $shipping_charge!=0)
                {
                    if($tax = Tax::where('id',$setting->shipping_tax)->where('status','active')->first())
                    {        
                        $shipping_tax = ($tax->percentage * ($shipping_charge / 100)); 
                        $shipping_charge = $shipping_charge + $shipping_tax; 
                    }
                }
            }
            $this->cart_products[$key]['shipping_gross_amount'] = $shipping_charge;
            $this->cart_products[$key]['shipping_tax'] = $shipping_tax??0;
            $shipping_charges += $shipping_charge;
        }

        $this->shipping_charges = $shipping_charges??0;

        if(!empty($this->coupon_code))
        {
            $is_applied = new CouponApply();
            $result  = $is_applied->CheckIsAppliedCoupon($this->total_price);
            if($result=='success'){
                $this->CouponApplied('true');
            }
        }

    }

    public function completeOrder(){

        $this->validate(['address_id'=>['required','not_in:0', function ($attribute, $value, $fail) {
            if (! $this->pointInPolygon($value)) {
                    $fail('Shipment not available here.');
                }
            }]
        ]);

        $coupon = Coupon::where('coupon_code',$this->coupon_code)->first();
        $setting = Setting::first();
        $carts = $this->cart_products;


        if($this->place_order=='common'){

            $orderData['user_id'] = auth()->user()->id;
            $orderData['coupon_code'] = $this->coupon_code;
            $orderData['sub_total'] = $this->total_price;
            $orderData['total_amount'] = ($this->total_price - $this->coupon_discount)+$this->shipping_charges;
            $orderData['discount_amount'] = $this->coupon_discount;
            $orderData['description'] = auth()->user()->usercart->notes??'';
            $orderData['shipping_amount'] = $this->shipping_charges;
            $orderData['is_confirmed'] = 1;
            $orderData['status'] = 'new_request';
            
            $order = Order::create($orderData);
            $order_id = $order->id;

            $paymentData['order_id'] = $order_id;
            $paymentData['user_id'] = auth()->user()->id;
            $paymentData['currency'] = 'INR';
            $paymentData['charge_id'] = $this->payment_id??'';
            $paymentData['payment_chennal'] = empty($this->payment_id)?'cod':'card';
            $paymentData['amount'] = $order->total_amount;
            $paymentData['status'] = empty($this->payment_id)?'pending':'completed';
            $paymentData['payment_type'] = empty($this->payment_id)?'pending':'confirm';
            $payment = OrderPayment::create($paymentData);
            $order_code = $this->generateOrderCode($order->id);
    
            Order::where('id',$order->id)->update(['code'=>$order_code,'payment_id'=>$payment->id]);
            
            foreach($carts as $cart)
            {
                $attribute_set_ids = ProductAttributeSet::whereProductVariantId($cart['variant_id'])->pluck('attribute_set_id')->toArray();
                $productvariant = ProductVariant::where('id',$cart['variant_id'])->select('price','sale_price','discount_duration','discount_start_date','discount_end_date','shipping_weight as weight','shipping_wide as wide','shipping_height as height','shipping_length as length')->first();
                $tax = Tax::find($cart['tax_ids']);
                $shippingtax = Tax::where('id',$setting->shipping_tax)->where('status','active')->first();

                $price = $productvariant->price;
                $sale_price = 0;

                if($productvariant->sale_price!=0){
                    
                    if($productvariant->discount_duration=='yes'){

                        $currentDate = Carbon::now()->format('d-m-Y H:i');

                        // Start and end date from user input or database
                        $startDate = Carbon::parse($productvariant->discount_start_date)->format('d-m-Y H:i'); 
                        $endDate = Carbon::parse($productvariant->discount_end_date)->format('d-m-Y H:i'); 

                        // Validate start date
                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                            $sale_price = $price = $productvariant->sale_price;
                        } 

                    }else{
                        $sale_price = $price = $productvariant->sale_price;
                    }
                    
                }
                
                $discount = $cart['discount_coupon']??0;
                $shipping_tax = $cart['shipping_tax'];
                $shipping_gross_amount = $cart['shipping_gross_amount'];
                $shipping_charge = $shipping_gross_amount - $shipping_tax;
                $shipping_discount = (isset($cart['free_shipping'])&&$cart['free_shipping']==0)?$shipping_gross_amount:0;

                // Coupon discount check
                if($this->apply_for=='all' && !empty($this->coupon_code)){
                    $discount_type = $coupon->discount_type;
                    $coupon_discount = $coupon->discount;
                    if($discount_type=='flat'){
                        $discount = $coupon_discount / count($carts);
                    }elseif($discount_type=='free_shipping'){
                        $shipping_charges = 0;
                        $shipping_discount = $shipping_gross_amount;
                    }elseif($discount_type=='percentage'){
                        $discount = ($this->total_price * ($discount / 100)) / count($carts);
                    }
                }
                $orderitem["order_id"] = $order_id;
                $orderitem["product_id"] = $cart['id'];
                $orderitem['product_name'] = $cart['name'];
                $orderitem['product_image'] = $cart['image'];
                $orderitem['quantity'] = $cart['quantity'];
                $orderitem['weight'] = $productvariant->weight*$cart['quantity'];
                $orderitem['wide'] = $productvariant->wide*$cart['quantity'];
                $orderitem['height'] = $productvariant->height*$cart['quantity'];
                $orderitem['length'] = $productvariant->length*$cart['quantity'];
                $orderitem["price"] = $productvariant->price;
                $orderitem["sale_price"] = $sale_price;
                $orderitem["tax"] = $tax->name??'';
                $orderitem["tax_id"] = $tax->id??0;
                $orderitem["taxable_amount"] = $price * $cart['quantity'];
                $orderitem["tax_amount"] = ($tax->percentage * ($orderitem["taxable_amount"] / 100));
                $orderitem["gross_amount"] = $orderitem["tax_amount"] + $orderitem["taxable_amount"];
                $orderitem["discount_amount"] = $discount;
                $orderitem["sub_total"] = $orderitem["gross_amount"] - $orderitem["discount_amount"];

                $orderitem["shipping_charge"] = $shipping_charge;
                $orderitem["shipping_tax"] = $shippingtax->name??'';
                $orderitem["shipping_tax_id"] = $shippingtax->id??0;
                $orderitem["shipping_tax_amount"] = $shipping_tax??0;
                $orderitem["shipping_taxable_amount"] = $shipping_charge;
                $orderitem["shipping_gross_amount"] = $shipping_gross_amount;
                $orderitem["shipping_discount_amount"] = $shipping_discount;
                $orderitem["shipping_sub_total"] = $shipping_gross_amount - $shipping_discount;
                $orderitem["total_amount"] = $orderitem["sub_total"] + $orderitem["shipping_sub_total"];
                $orderitem["attribute_set_ids"] = implode(',',$attribute_set_ids);

                OrderItem::create($orderitem);
            }
    
            SavedAddress::query()
                ->where('id', $this->address_id)
                ->each(function ($oldRecord) use($order_id) {
                    $newRecord = $oldRecord->replicate();
                    unset($newRecord['is_default']);
                    $newRecord->setTable('shipping_addresses');
                    $newRecord->order_id = $order_id;
                    $newRecord->save();
                }); 
    
            UserCart::whereUserId(auth()->user()->id)->delete();
            CartItem::whereUserId(auth()->user()->id)->delete();
        
            $orderShipment['order_id'] = $order_id;
            $orderShipment['user_id'] = auth()->user()->id;
            $orderShipment['cod_amount'] = empty($this->payment_id)?$order->total_amount:0;
            $orderShipment['cod_status'] = empty($this->payment_id)?'pending':null;
            $orderShipment['cross_checking_status'] = 'pending';
            $orderShipment['tracking_id'] = $this->generateTrackingId($order_id);
            // $orderShipment['shipping_company_name'] = null;
            // $orderShipment['tracking_link'] = null;
            // $orderShipment['estimate_date_shipped'] = null;
            // $orderShipment['date_shipped'] = null;
            $orderShipment['note'] = auth()->user()->usercart->notes??'';
            $orderShipment['status'] = 'order_placed';

            OrderShipment::create($orderShipment);

        }else{

            foreach($carts as $cart)
            {
                    
                $attribute_set_ids = ProductAttributeSet::whereProductVariantId($cart['variant_id'])->pluck('attribute_set_id')->toArray();
                $productvariant = ProductVariant::where('id',$cart['variant_id'])->select('price','sale_price','discount_duration','discount_start_date','discount_end_date','shipping_weight as weight','shipping_wide as wide','shipping_height as height','shipping_length as length')->first();
                $tax = Tax::find($cart['tax_ids']);
                $shippingtax = Tax::where('id',$setting->shipping_tax)->where('status','active')->first();

                $price = $productvariant->price;
                $sale_price = 0;
                
                if($productvariant->sale_price!=0){
                    
                    if($productvariant->discount_duration=='yes'){

                        $currentDate = Carbon::now()->format('d-m-Y H:i');

                        // Start and end date from user input or database
                        $startDate = Carbon::parse($productvariant->discount_start_date)->format('d-m-Y H:i'); 
                        $endDate = Carbon::parse($productvariant->discount_end_date)->format('d-m-Y H:i'); 

                        // Validate start date
                        if ($startDate <= $currentDate && $currentDate <= $endDate) {
                            $sale_price = $price = $productvariant->sale_price;
                        } 
                    }else{
                        $sale_price = $price = $productvariant->sale_price;
                    }
                    
                }
                $discount = $cart['discount_coupon']??0;
                $shipping_tax = $cart['shipping_tax'];
                $shipping_gross_amount = $cart['shipping_gross_amount'];
                $shipping_charge = $shipping_gross_amount - $shipping_tax;
                $shipping_discount = (isset($cart['free_shipping'])&&$cart['free_shipping']==0)?$shipping_gross_amount:0;

                // Coupon discount check
                if($this->apply_for=='all' && !empty($this->coupon_code)){
                    $discount_type = $coupon->discount_type;
                    $coupon_discount = $coupon->discount;
                    if($discount_type=='flat'){
                        $discount = $coupon_discount / count($carts);
                    }elseif($discount_type=='free_shipping'){
                        $shipping_charges = 0;
                        $shipping_discount = $shipping_gross_amount;
                    }elseif($discount_type=='percentage'){
                        $discount = ($this->total_price * ($discount / 100)) / count($carts);
                    }
                }
                $orderitem["product_id"] = $cart['id'];
                $orderitem['product_name'] = $cart['name'];
                $orderitem['product_image'] = $cart['image'];
                $orderitem['quantity'] = $cart['quantity'];
                $orderitem['weight'] = $productvariant->weight*$cart['quantity'];
                $orderitem['wide'] = $productvariant->wide*$cart['quantity'];
                $orderitem['height'] = $productvariant->height*$cart['quantity'];
                $orderitem['length'] = $productvariant->length*$cart['quantity'];
                $orderitem["price"] = $productvariant->price;
                $orderitem["sale_price"] = $sale_price;
                $orderitem["tax"] = $tax->name??'';
                $orderitem["tax_id"] = $tax->id??0;
                $orderitem["taxable_amount"] = $price * $cart['quantity'];
                $orderitem["tax_amount"] = (($tax->percentage??0) * ($orderitem["taxable_amount"] / 100));
                $orderitem["gross_amount"] = $orderitem["tax_amount"] + $orderitem["taxable_amount"];
                $orderitem["discount_amount"] = $discount;
                $orderitem["sub_total"] = $orderitem["gross_amount"] - $orderitem["discount_amount"];

                $orderitem["shipping_charge"] = $shipping_charge;
                $orderitem["shipping_tax"] = $shippingtax->name??'';
                $orderitem["shipping_tax_id"] = $shippingtax->id??0;
                $orderitem["shipping_tax_amount"] = $shipping_tax??0;
                $orderitem["shipping_taxable_amount"] = $shipping_charge;
                $orderitem["shipping_gross_amount"] = $shipping_gross_amount;
                $orderitem["shipping_discount_amount"] = $shipping_discount;
                $orderitem["shipping_sub_total"] = $shipping_gross_amount - $shipping_discount;
                $orderitem["total_amount"] = $orderitem["sub_total"] + $orderitem["shipping_sub_total"];
                $orderitem["attribute_set_ids"] = implode(',',$attribute_set_ids);


                $orderData['user_id'] = auth()->user()->id;
                $orderData['coupon_code'] = $this->coupon_code;
                $orderData['sub_total'] = $orderitem["sub_total"];
                $orderData['total_amount'] = $orderitem["total_amount"];
                $orderData['discount_amount'] = $discount;
                $orderData['shipping_amount'] = $orderitem["shipping_sub_total"];
                $orderData['description'] = auth()->user()->usercart->notes??'';
                $orderData['is_confirmed'] = 1;
                $orderData['status'] = 'new_request';
                
                $order = Order::create($orderData);
                $order_id = $order->id;

                $paymentData['order_id'] = $order_id;
                $paymentData['user_id'] = auth()->user()->id;
                $paymentData['currency'] = 'INR';
                $paymentData['charge_id'] = $this->payment_id??'';
                $paymentData['payment_chennal'] = empty($this->payment_id)?'cod':'card';
                $paymentData['amount'] = $order->total_amount;
                $paymentData['status'] = empty($this->payment_id)?'pending':'completed';
                $paymentData['payment_type'] = empty($this->payment_id)?'pending':'confirm';
                $payment = OrderPayment::create($paymentData);
                $order_code = $this->generateOrderCode($order->id);
        
                Order::where('id',$order->id)->update(['code'=>$order_code,'payment_id'=>$payment->id]);
                
                $orderitem["order_id"] = $order_id;

                OrderItem::create($orderitem);

                $orderShipment['order_id'] = $order_id;
                $orderShipment['user_id'] = auth()->user()->id;
                $orderShipment['cod_amount'] = empty($this->payment_id)?$order->total_amount:0;
                $orderShipment['cod_status'] = empty($this->payment_id)?'pending':null;
                $orderShipment['cross_checking_status'] = 'pending';
                $orderShipment['tracking_id'] = $this->generateTrackingId($order_id);
                // $orderShipment['shipping_company_name'] = null;
                // $orderShipment['tracking_link'] = null;
                // $orderShipment['estimate_date_shipped'] = null;
                // $orderShipment['date_shipped'] = null;
                $orderShipment['note'] = auth()->user()->usercart->notes??'';
                $orderShipment['status'] = 'order_placed';

                OrderShipment::create($orderShipment);
                    
                SavedAddress::query()
                    ->where('id', $this->address_id)
                    ->each(function ($oldRecord) use($order_id) {
                        $newRecord = $oldRecord->replicate();
                        unset($newRecord['is_default']);
                        $newRecord->setTable('shipping_addresses');
                        $newRecord->order_id = $order_id;
                        $newRecord->save();
                    }); 
        
            }
    
            UserCart::whereUserId(auth()->user()->id)->delete();
            CartItem::whereUserId(auth()->user()->id)->delete();

        }


        $this->emit('clearCart',$order_code);

    }
    
    public function initiatePayment()
    {      
        $this->validate(['address_id'=>['required','not_in:0', function ($attribute, $value, $fail) {
            if (! $this->pointInPolygon($value)) {
                    $fail('Shipment not available here.');
                }
            }]
        ]);
        if(config('shipping.payment_platform')=='razorpay')
        {
            $api = new Api(config('shipping.razorpay.razorpay_key'), config('shipping.razorpay.razorpay_secret'));
            $address = SavedAddress::find($this->address_id);
            $amount = (($this->total_price - $this->coupon_discount)+$this->shipping_charges).'00';
            $order = $api->order->create([
                'receipt' => 'order_rcptid_' . time(),
                'amount' => $amount,
                'currency' => 'INR',
            ]);

            $address->orderId = $order['id'];
            $address->amount = $order['amount'];

            $this->emit('initiateRazorpay',$address);
        }
    
    }
    
    public function pointInPolygon($address_id) 
    {
        $zipcode = SavedAddress::where('id',$address_id)->pluck('zip_code')->first();
        if($address_id){
            
            $this->zone = null;
            
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$zipcode."&sensor=false&key=".config('shipping.google_map_api_key');
            $details=file_get_contents($url);
            $result = json_decode($details,true);
        
            $this->lat = $result['results'][0]['geometry']['location']['lat']??0;
            $this->lng = $result['results'][0]['geometry']['location']['lng']??0;

            $x = $this->lat;
            $y = $this->lng;
            $inside = false;

            $zones = Zone::whereStatus('active')->get();

            foreach($zones as $zone){

                $polygon = json_decode($zone->zone_coordinates);
                $vertices = count($polygon);
                if(!$inside){
                    for ($i = 0, $j = $vertices - 1; $i < $vertices; $j = $i++) {
                        
                        $xi = $polygon[$i]->lat;
                        $yi = $polygon[$i]->lng;
                        $xj = $polygon[$j]->lat;
                        $yj = $polygon[$j]->lng;
                
                        $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
                    
                        if ($intersect) $inside = !$inside;
                    }
                    if ($inside) {
                        $this->zone = $zone->id;
                        break; // Stop looping once inside is true
                    }
                }
                
            }
            return $inside;
        }
    }
    
    public function CouponApplied($emit='false'){
        $this->coupon_discount = 0;
        $this->coupon_code = auth()->user()->usercart->coupon_code??'';
        $cart_product = auth()->user()->usercart->applicable_products??'';
        if(!empty($this->coupon_code))
        {
            $coupon = Coupon::where('coupon_code',$this->coupon_code)->first();
            $cart_product = array_filter(explode(',',$cart_product));
            $discount_type = $coupon->discount_type;
            $discount = $coupon->discount;
            if($discount_type=='flat' && count($cart_product)==0){
                $this->coupon_discount = $discount;
                $this->apply_for = 'all';
            }elseif($discount_type=='free_shipping' && count($cart_product)==0){
                $this->shipping_charges = 0;
                $this->apply_for = 'all';
            }elseif($discount_type=='percentage' && count($cart_product)==0){
                $this->coupon_discount = $this->total_price * ($discount / 100);
                $this->apply_for = 'all';
            }else{
                if($discount_type=='flat'){
                    if(count($cart_product)!=0){
                        foreach($cart_product as $cartp){
                            $prod_price = ($this->cart_products[$cartp]['discount']!=0)?$this->cart_products[$cartp]['sale_price']:$this->cart_products[$cartp]['price'];
                            $this->cart_products[$cartp]['discount_coupon'] = $discount/count($cart_product);
                        }
                        $this->coupon_discount = $discount;
                    }                        
                    $this->apply_for = 'separate';
                }elseif($discount_type=='free_shipping'){
                    if(count($cart_product)!=0){
                        foreach($cart_product as $cartp){
                            $this->shipping_charges -= $this->cart_products[$cartp]['shipping_gross_amount'];
                            $this->cart_products[$cartp]['free_shipping'] = 0;
                        }
                    }
                    $this->apply_for = 'separate';
                }else{
                    if(count($cart_product)!=0){
                        $price = 0;
                        foreach($cart_product as $cartp){
                            $prod_price = ($this->cart_products[$cartp]['discount']!=0)?$this->cart_products[$cartp]['sale_price']:$this->cart_products[$cartp]['price'];
                            $this->cart_products[$cartp]['discount_coupon'] = $prod_price * ($discount / 100);
                            $price += $prod_price;
                        }
                        $this->coupon_discount = $price * ($discount / 100);
                        $this->apply_for = 'separate';
                    }
                }
            }
        }

        if($emit=='false'){
            $this->emit('appliedCouponSuccessToast',$this->coupon_code);
        }
    }
    public function removeCoupon(){
        $this->coupon_code = '';
        $this->coupon_error = '';
        $this->coupon_discount = 0;        
        UserCart::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['coupon_code'=>null,'applicable_products'=>null]
        );
        $this->calculateShippingCharges();
    }

    private function generateOrderCode($counter) {
        return 'ORD-' . str_pad($counter, 8, '0', STR_PAD_LEFT);
    }

    private function generateTrackingId($counter) {
        return 'TRC-' . str_pad($counter, 12, '0', STR_PAD_LEFT);
    }

    public function mount($from='')
    {
        $this->from = $from;
        $this->address_id = auth()->user()->usercart->address->id??(auth()->user()->address->id??0);
        $this->coupon_code = auth()->user()->usercart->coupon_code??'';
        $this->cartList();
        $this->addressList();
    }

    public function render()
    {
        $this->address_id = auth()->user()->usercart->address->id??(auth()->user()->address->id??0);
        return view('livewire.ecommerce.product.checkout');
    }

}
