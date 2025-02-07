<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Cart as CartItem;
use App\Models\UserCart;
use App\Models\Product;
use App\Models\ProductAttributeSet;
use App\Models\AttributeSet;
use App\Models\SavedAddress;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use App\Models\Tax;
use Carbon\Carbon;
use App\Traits\ZoneConfig;

class Cart extends Component
{
    use ZoneConfig;

    public $cart_products = [];
    
    public $warehouse_ids = [];
    
    public $total_price = 0;

    public $coupon_code,$notes;

    protected $listeners = ['ReplaceItem','cartList','CouponApplied'];

    public function ReplaceItem($cart_id,$variant_id,$qty)
    {
        CartItem::where('id',$cart_id)->update(['product_variant_id'=>$variant_id,'quantity'=>$qty]);
        $this->cartList();
    }

    public function cartList()
    {
        $this->coupon_code = auth()->user()->usercart->coupon_code??'';
        
        $datas = CartItem::whereUserId(auth()->user()->id)->get()->toArray();

        $cart_products = [];

        $total_price = 0;

        foreach($datas as $data)
        {

            if(Product::where('id',$data['product_id'])->exists() && ProductVariant::where('id',$data['product_variant_id'])->exists()){
           
                $product = Product::where('id',$data['product_id'])->select('id','slug','name','images','related_product_ids','tax_ids','created_at')->first()->toArray();
        
                $default = ProductVariant::where('id',$data['product_variant_id'])->select('price','cart_limit','sale_price','discount_expired','discount_start_date','discount_end_date','discount_duration')->first();

                $discount = $price = $sale_price = 0;
                
                if(isset($default))
                {                    
                    $product_stock = ProductStock::select('id', 'available_quantity')
                                                ->whereIn('warehouse_id',$this->warehouse_ids)
                                                ->whereProductVariantId($data['product_variant_id'])
                                                ->groupBy('id', 'available_quantity')
                                                ->orderBy('available_quantity','desc')
                                                ->first();
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
                                $discount = ($sale_price/$price)*100;
                            } 

                        }else{
                            $sale_price = $default->sale_price;
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

                    $images = json_decode($product['images'], true);
                    $product['cart_id'] =  $data['id'];
                    $product['image'] = (isset($images[0]))?asset('storage').'/'.$images[0]:asset('asset/home/default-hover1.png');
                    $product['variant_id'] = $data['product_variant_id'];
                    $product['slug'] = $product['slug'];
                    $product['price'] = $price;
                    $product['sale_price'] = $sale_price;
                    $product['discount'] = ($discount!=0)?(100 - round($discount)):0;
                    $product['quantity'] = $data['quantity'];
                    $product['attributes'] = implode(', ',$attribute_set_name);
                    $product['total_price'] = (($discount!=0)?($data['quantity']*$sale_price):($data['quantity']*$price));
                    $total_price += $product['total_price'];
                    $product['product_type'] = ProductVariant::whereProductId($data['product_id'])->count();
                    $product['stock_status'] = (isset($product_stock))?(($product_stock->available_quantity!=0)?'in_stock':'out_of_stock'):'out_of_stock';
                    $product['available_quantity'] = $product_stock->available_quantity??0;
                    $product['product_stock_id'] = $product_stock->id??0;
                    $product['cart_limit'] = $default->cart_limit??0;

                    $cart_products[] = $product;
                            
                }
                
            }else{
                CartItem::where('id',$data['id'])->delete(); 
            }
        
           
        }
        $this->total_price = $total_price;
        $this->cart_products = $cart_products;
        $this->coupon_code = auth()->user()->usercart->coupon_code??'';

        if(!empty($this->coupon_code)){
            $is_applied = new CouponApply();
            $result  = $is_applied->CheckIsAppliedCoupon($total_price);
            if($result=='success'){
                $this->CouponApplied('true');
            }
        }

    }
    public function removeCoupon(){
        $this->coupon_code = '';    
        UserCart::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['coupon_code'=>null,'applicable_products'=>null]
        );
    }
    public function CouponApplied($emit='false'){
        $this->coupon_code = auth()->user()->usercart->coupon_code??'';

        if($emit=='false'){
            $this->emit('appliedCouponSuccessToast',$this->coupon_code);
        }  
    }
    public function Checkout()
    {

        $this->resetValidation();
        $validateData = $this->validate([
            'notes' => 'nullable|min:10|max:180',
        ], [
            'notes.min' => 'Notes must be at least 10 characters',
            'notes.max' => 'Notes must be less than 180 characters.',
        ]);
        if(!empty($this->coupon_code)){
            $validateData['coupon_code'] = $this->coupon_code;
        }

        UserCart::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $validateData
        );
        
        return redirect()->to('/checkout');

    }

    public function mount()
    {
        $zone = \Session::get('zone_config');
        $this->warehouse_ids = array_filter(explode(',',$zone['warehouse_ids']));

        $this->cartList();

        $usercart = UserCart::whereUserId(auth()->user()->id)->first();

        if(isset($usercart)){
            $this->coupon_code = $usercart->coupon_code;
            $this->notes = $usercart->notes;
            $this->coupon_error = (!empty($usercart->coupon_code))?'valid_coupon':'';
        }

    }
    
    public function render()
    {
        return view('livewire.ecommerce.product.cart');
    }
}
