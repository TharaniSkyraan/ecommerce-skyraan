<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\Component;
use App\Models\Cart as CartItem;
use App\Models\UserCart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Carbon\Carbon;

class CouponApply extends Component
{
    
    protected $listeners = ['availableCoupon'];

    public $coupon_code,$coupon_error,$total_price;

    public $available_coupons=[];
    
    public function availableCoupon($total_price)
    {
        $this->coupon_error = '';
        $this->total_price = $total_price;
        $this->coupon_code = auth()->user()->usercart->coupon_code??'';
        $cart_product_ids = CartItem::whereUserId(auth()->user()->id)->pluck('product_id')->toArray();
        $user_id = auth()->user()->id;

        // coupon ids        
        $user_coupon_ids = Coupon::withCount('user_orders')->whereStatus('active')->havingRaw("user_orders_count < 1")->where('apply_for','once-per-customer')->where('display_at_checkout','yes')->pluck('id')->toArray();
       
        $customer_coupon_ids = Coupon::where('apply_for','customer')->whereStatus('active')->where('display_at_checkout','yes')->where('apply_for_ids', 'REGEXP', $user_id)->pluck('id')->toArray();
     
        $coupon_ids = Coupon::where('display_at_checkout','yes')->whereStatus('active')->whereNotIn('apply_for',['customer','once-per-customer'])->pluck('id')->toArray();
     
        $coupon_ids = array_merge($user_coupon_ids, $customer_coupon_ids, $coupon_ids);
    
        $coupons = Coupon::whereIn('id',$coupon_ids)->get()->each(function ($items) {
            $items->append(['expired_status','apply_for_product']);
        })->toArray();

        $available_coupons = array_map(function ($coupon) use($cart_product_ids) 
        {
            if($coupon['expired_status']==false){
                if($coupon['apply_for']!='customer' && $coupon['apply_for']!='once-per-customer' && $coupon['apply_for']!='minimum-order'){
                    $product_ids = array_filter(explode(',',$coupon['apply_for_product']));
                    $matchingProduct = array_intersect($cart_product_ids, $product_ids);
                    if(count($matchingProduct)!=0){
                        $coupon['product_names'] = implode(', ',Product::whereIn('id',$matchingProduct)->pluck('name')->toArray());
                        return $coupon;
                    }
                }else{
                    return $coupon;
                }                
            }
        }, $coupons);

        $this->available_coupons = array_filter($available_coupons, function($value) {
            return $value !== null;
        });

    }

    public function CheckIsAppliedCoupon($price=0)
    { 
        if($price!=0){
            $this->total_price = $price;
        }
        if(isset(auth()->user()->usercart))
        {
            if(!empty(auth()->user()->usercart->coupon_code)){
                $this->coupon_code = auth()->user()->usercart->coupon_code??'';
                $apply = $this->applyCoupon($this->coupon_code,'true');

                if($apply=='success'){
                    return 'success';
                }else{
                    return 'fail';
                }
            }
        }
    }

    public function applyCoupon($coupon_code='',$emit='false')
    {
        if(!empty($coupon_code)){   
            $this->coupon_code = $coupon_code;
        }
        $this->resetValidation('coupon_code');
        $this->coupon_error = '';
        
        if($emit=='false'){
            $validateData = $this->validate([
                'coupon_code' => 'required|exists:coupons,coupon_code,deleted_at,NULL,status,active', 
            ], [
                'coupon_code.exists' => 'Please try again! Invalid coupon code.'
            ]);
        }

        $coupon = Coupon::whereCouponCode($this->coupon_code)->whereStatus('active')->first();

        if((isset($coupon) && $coupon->isExpired()) || !isset($coupon))
        {
            $this->coupon_error = 'Please enter valid coupon code';
        }else
        {
            if($coupon->unlimited_coupon=='no' && ($coupon->used_count >= $coupon->count))
            {
                $this->coupon_error = 'Please enter valid coupon code';
            }
            if($coupon->above_order > $this->total_price && $coupon->discount_type=='flat'){
                $this->coupon_error = 'Your order should be above '.$coupon->above_order;
            }
            $apply_for = $coupon->apply_for;
            $discount = 0;
         
            switch ($apply_for) 
            {
                case 'all-orders':
                    $discount = $coupon->discount;
                    break;
                case 'minimum-order':
                    if($coupon->minimum_order > $this->total_price){
                        $this->coupon_error = 'Your order should be minimum '.$coupon->minimum_order;
                    }
                    $discount = $coupon->discount;                        
                    break;
                case 'collection':
                    $collection_product_ids = Collection::where('id',$coupon->apply_for_ids)->pluck('product_ids')->first();
                    if(isset($collection_product_ids)){
                        $product_ids = CartItem::whereIn('product_id',explode(',',$collection_product_ids))
                                            ->whereUserId(auth()->user()->id)
                                            ->pluck('product_id')->toArray();
                        if(count($product_ids)!=0){
                            $discount = $coupon->discount;  
                        }else{                        
                            $this->coupon_error = 'Please try again! Invalid coupon code.';
                        }
                    }else{                        
                        $this->coupon_error = 'Please try again! Invalid coupon code.';
                    }                       
                    break;
                case 'category':
                    $apply_for_ids = $coupon->apply_for_ids;

                    //Apply for all sub category
                    // $apply_for_sub_ids = Category::where('parent_id',$apply_for_ids)->pluck('id')->toArray();

                    $cart_product_ids = CartItem::whereUserId(auth()->user()->id) ->pluck('product_id')->toArray();

                    $product_ids = Product::where('category_ids', 'like', '%,'.$apply_for_ids.',%')
                                            // ->where(function($q) use ($apply_for_ids, $apply_for_sub_ids) {
                                            //     $q->where('category_ids', 'like', '%,'.$apply_for_ids.',%');
                                            //     foreach ($apply_for_sub_ids as $sub_id) {
                                            //         $q->orWhere('category_ids', 'like', '%,'.$sub_id.',%');
                                            //     }
                                            // })
                                            ->whereIn('id',$cart_product_ids)->pluck('id')->toArray();
                    if(count($product_ids)!=0){
                        $discount = $coupon->discount;  
                    }else{                        
                        $this->coupon_error = 'Please try again! Invalid coupon code.';
                    }
                    break;
                case 'product':                                           
                    $product_ids = CartItem::whereIn('product_id',explode(',',$coupon->apply_for_ids))
                                        ->whereUserId(auth()->user()->id)
                                        ->pluck('product_id')->toArray();
                   
                    if(count($product_ids)!=0){
                        $discount = $coupon->discount;  
                    }else{
                        $this->coupon_error = 'Please try again! Invalid coupon code.';
                    } 
                    break;
                case 'customer':                 
                    $user_ids = explode(',',$coupon->apply_for_ids);
                    if(in_array(auth()->user()->id, $user_ids)) {
                        $discount = $coupon->discount;  
                    }else{
                        $this->coupon_error = 'Please try again! Invalid coupon code.';
                    }                
                    break;
                case 'once-per-customer':
                    $order = Order::whereCouponCode($this->coupon_code)
                                    ->whereUserId(auth()->user()->id)
                                    ->withTrashed()
                                    ->count();
                    if($order==0){
                        $discount = $coupon->discount;  
                    }else{                        
                        $this->coupon_error = 'Please try again! Invalid coupon code.';
                    }
                    break;
            }


            if($discount==0 && empty($this->coupon_error)){
                if($coupon->discount_type!='free_shipping'){
                    $this->coupon_error = 'The coupon code is invalid';
                }
            }
        }
        if(empty($this->coupon_error)){

            UserCart::updateOrCreate(
                ['user_id' => auth()->user()->id],
                ['coupon_code'=>$this->coupon_code, 'applicable_products'=> (isset($product_ids))?implode(',',$product_ids):null]
            );
            $this->coupon_code = '';

            if($emit!='true'){
                $this->emit('appliedCouponSuccess','succeess');
            }
        }else{

            UserCart::updateOrCreate(
                ['user_id' => auth()->user()->id],
                ['coupon_code'=>null,'applicable_products'=>null],
            );
        }
        
        if($emit=='true'){
            if(empty($this->coupon_error)){
                return 'success';
            }else{
                return 'fail';                
            }
        }
    }
    public function render()
    {
        return view('livewire.ecommerce.product.coupon-apply');
    }
}
