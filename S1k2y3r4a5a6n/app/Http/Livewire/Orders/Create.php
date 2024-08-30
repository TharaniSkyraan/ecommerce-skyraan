<?php

namespace App\Http\Livewire\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingStatus;
use App\Models\OrderShipment;
use App\Models\ShippingHistory;
use App\Models\OrderHistory;
use App\Models\ProductStock;
use App\Models\StockHistory;
use App\Models\ProductVariant;
use App\Models\OrderPayment;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\ShippingAddress;
use App\Models\SavedAddress;
use App\Models\ProductAttributeSet;
use App\Models\Tax;
use App\Models\Product;
use App\Models\ProductStockUpdateQuantityHistory;
use Carbon\Carbon;

class Create extends Component
{
   
    public $warehouses,$query,$warehouse_id,$have_shipping_charge,$customer_id,$customer_name,$customer_email,$customer_address,$customer_phone,
    $suggestion,$total_amount,$sub_amount,$shipping_amount;
    public $customer_data = false;
    public $products = [];
    public $selected_products = [];
    public $suggesstion = false;
    protected $listeners = ['suggestion','unsetsuggestion', 'resetInputvalues'];
    
    public function updatedCustomerPhone(){
        $this->customer_data = false;
        $this->validateOnly('customer_phone', [
            'customer_phone' => 'required|numeric|phone:IN',
        ], [
            'customer_phone.required' => 'Phone number is required',
            'customer_phone.numeric' => 'Please enter a valid phone number',
            'customer_phone.unique' => 'The given phone number already exists',
            'customer_phone.phone' => 'Please enter a valid phone number',
        ]);

        $this->customer_data = true;
        $user = User::wherePhone($this->customer_phone)->first();

        $this->customer_name = $user->name??'';
        $this->customer_id = $user->id??null;
        $this->customer_email = $user->email??'';
        $this->customer_address = (isset($user->address))?$user->address->address:'';
    }
    
    public function updatedCustomerEmail(){
        
        $user = User::whereEmail($this->customer_email)->first();
       
        $this->customer_id = $user->id??null;
        $this->customer_address = (isset($user->address))?$user->address->address:'';
        $this->customer_name = $user->name??'';
        if(empty($this->customer_phone)){
            $this->customer_phone = $user->phone??'';
        }
    }
    public function updatedQuery(){
        if(!empty($this->query)){
            $query = $this->query;
            $this->products = ProductVariant::with('product')
            ->where('product_name', 'like', "%{$query}%")
            ->orwhere(function($q1) use($query){
                $q1->whereHas('product', function($q) use($query){
                    $q->where('name', 'like', "%{$query}%");
                });
            })->get();
        }
    }

    public function suggestion(){
        $this->suggesstion = true;
    }

    public function unsetsuggestion(){
        $this->suggesstion = false;
    }
    public function ProductsArray($variant_id)
    {
        $this->validate([
            'warehouse_id' => 'required',
        ]);
     
        $productVariant = ProductVariant::find($variant_id);
        $product = $productVariant->product;
        $index = $productVariant->id.'-'.$this->warehouse_id;
        
        if(isset($this->selected_products[$index]))
        {
            unset($this->selected_products[$index]);
        }else
        {
        
            $available_stock = ProductStock::whereWarehouseId($this->warehouse_id)->whereProductVariantId($variant_id)->select('id','available_quantity')->first();
            $available_quantity = $available_stock->available_quantity??0;
            
            $price = $productVariant->price;

            if($productVariant->sale_price!=0 && $productVariant->discount_expired!='yes')
            {
                if($productVariant->discount_duration=='yes'){

                    $currentDate = Carbon::now()->format('d-m-Y H:i');

                    // Start and end date from user input or database
                    $startDate = Carbon::parse($productVariant->discount_start_date)->format('d-m-Y H:i'); 
                    $endDate = Carbon::parse($productVariant->discount_end_date)->format('d-m-Y H:i'); 

                    // Validate start date
                    if ($startDate <= $currentDate && $currentDate <= $endDate) {
                        $sale_price = $productVariant->sale_price;
                        $discount = (($price-$sale_price)/$price)*100;
                    } 

                }else{
                    $sale_price = $productVariant->sale_price;
                    $discount = (($price-$sale_price)/$price)*100;
                }

            }

            if(!empty($product->tax_ids))
            {
                if($tax = Tax::where('id',$product->tax_ids)->where('status','active')->first())
                {                    
                    $tax_price = ($tax->percentage * ($price / 100));
                    $price = $price + $tax_price;
                    if($sale_price!=0){
                        $sale_price = $sale_price + ($tax->percentage * ($sale_price / 100));
                    }  
                }
            }


            $data['product_name']= $productVariant->product_name;
            $data['product_id'] = $productVariant->product_id;
            $data['tax_price'] = $productVariant->tax_price;
            $data['variant_id'] = $productVariant->id;
            $data['available_stock'] = $available_quantity;
            $data['product_stock_id'] = $available_stock->id??'';
            $data['warehouse_id'] = $this->warehouse_id;
            $data['quantity'] = ($available_quantity!=0)?1:0;
            $data['price'] = $price;
            $data['sale_price'] = $sale_price;
            $data['shipping_charge'] = 0;
            $data['discount'] = ($discount!=0)?(round($discount)):0;
            $data['tax_ids'] = $product->tax_ids;
            $this->selected_products[$index] = $data;  

        }

    }
    public function updatedWarehouseId()
    {
        $this->selected_products = [];
    }
    
    public function resetInputvalues(){      
        $this->reset(['warehouse_id','selected_products','query','suggesstion']);  
    }   

    public function decreaseQuantity($index){
        if($this->selected_products[$index]['quantity']>1){
           $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['quantity'] - 1;
        }
    }

    public function increaseQuantity($index){
        $quantity = $this->selected_products[$index]['quantity'] + 1;
        if($this->selected_products[$index]['available_stock']>=$quantity){
            $this->selected_products[$index]['quantity'] = $quantity;
        }
    }

    public function updateQuantity($index){
        if($this->selected_products[$index]['available_stock'] < $this->selected_products[$index]['quantity']){
            $this->selected_products[$index]['quantity'] = $this->selected_products[$index]['available_stock'];
        }
    }
    
    public function removeProduct($index)
    {
        unset($this->selected_products[$index]);
    }

    public function store()
    {

        $validatedData = $this->validate([
            'customer_phone' => 'required|numeric|phone:IN',
            'customer_email' => 'required|string|max:180|email',
            'customer_name' => 'required|string|min:3|max:180',
            'customer_address' => 'required|string|min:3|max:255',
        ], [
            'customer_phone.required' => 'Phone number is required',
            'customer_phone.numeric'=> 'Please enter valid Phone Number',
            'customer_phone.unique' => 'The given phone number already exist',
            'customer_phone.phone' => 'Please enter valid Phone Number',
            'customer_email.unique' => 'The given email already exist',
            'customer_email.required' => 'Email id is required',
            'customer_name.required' => 'Name is required',
            'customer_address.required' => 'Address is required',
            'customer_address.min' => 'Address must be at least 3 characters',
            'customer_address.max' => 'Address must be less than 255 characters.',
        ]);
        
        if(empty($this->customer_id)){

            $user = User::wherePhone($this->customer_phone)->orwhere('email',$this->customer_email)->first();
            if(!isset($user)){
                $user = User::create(['name'=>$this->customer_name,'phone'=>$this->customer_phone,'email'=>$this->customer_email,'password'=>\Hash::make('12345678'),'signup_by'=>'admin']);
                SavedAddress::create(['user_id' => $user_id,'phone' => $this->customer_phone,'name' => $this->customer_name,'address' => $this->customer_address,'country' => '','city' => '','state' => '','postal_code' => '']);
            }
            $this->customer_id = $user->id;
        }
        $orderData['user_id'] = $this->customer_id;
        $orderData['is_confirmed'] = 1;
        $orderData['status'] = 'delivered';
        
        $order = Order::create($orderData);
        $order_id = $order->id;
        $order_code = $this->generateOrderCode($order->id);
        
        $shipping_charges = $total_price = 0;

        foreach($this->selected_products as $product)
        {
            $attribute_set_ids = ProductAttributeSet::whereProductVariantId($product['variant_id'])->pluck('attribute_set_id')->toArray();
            $productvariant = ProductVariant::where('id',$product['variant_id'])->select('product_name','price','images','sale_price','discount_duration','discount_start_date','discount_end_date','shipping_weight as weight','shipping_wide as wide','shipping_height as height','shipping_length as length')->first();
            
            $tax = Tax::find($product['tax_ids']);
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
            $shipping_charge = $product['shipping_charge'];
            $prdimage = Product::find($product['product_id']);
            $images = json_decode($productvariant->images, true);
            $images = (count($images)==0)?json_decode($prdimage->images, true):$images;
            $product['image'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
            
            $orderitem["order_id"] = $order_code;
            $orderitem["product_id"] = $product['product_id'];
            $orderitem['product_name'] = $productvariant->product_name;
            $orderitem['product_image'] = $product['image'];
            $orderitem['quantity'] = $product['quantity'];
            $orderitem['weight'] = $productvariant->weight*$product['quantity'];
            $orderitem['wide'] = $productvariant->wide*$product['quantity'];
            $orderitem['height'] = $productvariant->height*$product['quantity'];
            $orderitem['length'] = $productvariant->length*$product['quantity'];
            $orderitem["price"] = $productvariant->price;
            $orderitem["sale_price"] = $sale_price;
            $orderitem["tax"] = $tax->name??'';
            $orderitem["tax_id"] = $tax->id??0;
            $orderitem["taxable_amount"] = $price * $product['quantity'];
            $orderitem["tax_amount"] = (($tax->percentage??0) * ($orderitem["taxable_amount"] / 100));
            $orderitem["gross_amount"] = $orderitem["tax_amount"] + $orderitem["taxable_amount"];
            $orderitem["discount_amount"] = 0;
            $orderitem["sub_total"] = $orderitem["gross_amount"] - $orderitem["discount_amount"];

            $orderitem["shipping_charge"] = $shipping_charge;
            $orderitem["shipping_tax"] = '';
            $orderitem["shipping_tax_id"] = 0;
            $orderitem["shipping_tax_amount"] = 0;
            $orderitem["shipping_taxable_amount"] = $shipping_charge;
            $orderitem["shipping_gross_amount"] = $shipping_charge;
            $orderitem["shipping_discount_amount"] = 0;
            $orderitem["shipping_sub_total"] = $shipping_charge;
            $orderitem["total_amount"] = $orderitem["sub_total"] + $orderitem["shipping_sub_total"];
            $orderitem["attribute_set_ids"] = implode(',',$attribute_set_ids);

            $shipping_charges += $shipping_charge;
            $total_price += $orderitem["total_amount"];
            
            $product_stock_id = $product['product_stock_id'];

            $product_stock = ProductStock::find($product_stock_id);
            $warehouse_id = $product_stock->warehouse_id;
            $available_stock = $product_stock->available_quantity;
            $product_stock->available_quantity -= $product['quantity'];
            $product_stock->stock_status = ($product_stock->available_quantity<1)?'out_of_stock':'in_stock';
            $product_stock->save();

            $date = Carbon::now();

            $stock_history =  StockHistory::updateOrCreate([
                'reference_number' => $order_id,
                'warehouse_to_id' => $warehouse_id,
                'stock_type' => 'order',
            ],[
                'warehouse_from_id' => 0,
                'sent_date' => $date,
                'received_date' => $date,
                'status' => 'delivered'
            ]);

            // Product Stock Quantity Update History
            $quantity_update_history = ProductStockUpdateQuantityHistory::create([
                'history_id' => $stock_history->id,
                'warehouse_id' => $warehouse_id,
                'product_name' => $productvariant->product_name,
                'product_id' => $product['product_id'],
                'product_variant_id' => $product['variant_id'],
                'previous_available_quantity' => $available_stock,
                'updated_quantity' => $product['quantity'],
                'available_quantity' => $available_stock - $product['quantity'],
            ]);
                
            $orderitem["order_id"] = $order_id;
            $orderitem["warehouse_id"] = $warehouse_id;

            OrderItem::create($orderitem);

        }
        $total_amount = $total_price+$shipping_charges;
        $paymentData['order_id'] = $order_id;
        $paymentData['user_id'] = $this->customer_id;
        $paymentData['currency'] = 'INR';
        $paymentData['charge_id'] = '';
        $paymentData['payment_chennal'] = 'cod';
        $paymentData['amount'] = $total_amount;
        $paymentData['status'] = 'completed';
        $paymentData['payment_type'] = 'confirm';
        $payment = OrderPayment::create($paymentData);

        Order::where('id',$order->id)->update(['code'=>$order_code,
                                                'sub_total' => $total_price,
                                                'total_amount' => $total_amount,
                                                'discount_amount' => 0,
                                                'description' => '',
                                                'shipping_amount' => $shipping_charges,
                                                'invoice_number' => $this->generateInvoiceNumber($order_id),
                                                'invoice_date' => Carbon::now(),
                                                'payment_id'=>$payment->id]);
       
        if($this->have_shipping_charge){
            ShippingAddress::create([
                'order_id' => $order_id,
                'user_id' => $this->customer_id,
                'phone' => $this->customer_phone,
                'name' => $this->customer_name,
                'address' => $this->customer_address,
                'country' => '',
                'city' => '',
                'state' => '',
                'postal_code' => ''
            ]);    
        }

        $orderShipment['order_id'] = $order_id;
        $orderShipment['user_id'] = $this->customer_id;
        $orderShipment['cod_amount'] = $total_amount;
        $orderShipment['cod_status'] = 'completed';
        $orderShipment['cross_checking_status'] = 'pending';
        $orderShipment['tracking_id'] = $this->generateTrackingId($order_id);
        // $orderShipment['shipping_company_name'] = null;
        // $orderShipment['tracking_link'] = null;
        // $orderShipment['estimate_date_shipped'] = null;
        // $orderShipment['date_shipped'] = null;
        $orderShipment['note'] = '';
        $orderShipment['status'] = 'delivered';

        OrderShipment::create($orderShipment);

        session()->flash('message', 'Order placed successfully saved.');

        return redirect()->to('admin/orders');
    }

    private function generateOrderCode($counter) {
        return 'ORD-' . str_pad($counter, 8, '0', STR_PAD_LEFT);
    }

    private function generateTrackingId($counter) {
        return 'TRC-' . str_pad($counter, 12, '0', STR_PAD_LEFT);
    }

    private function generateInvoiceNumber($counter){
        return 'INV-' . str_pad($counter, 12, '0', STR_PAD_LEFT);
    }

    public function mount()
    {
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $this->warehouses = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->get();
        }
        else{
            $this->warehouses = Warehouse::all();
        }        
    }

    public function render()
    {
        \Log::info($this->total_amount);
        return view('livewire.orders.create');
    }
}
