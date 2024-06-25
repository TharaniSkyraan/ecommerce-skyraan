<?php

namespace App\Http\Livewire\Coupon;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Coupon;
use App\Models\User as Customer;
use Carbon\Carbon;

class Create extends Component
{
    public $today,$coupon_id,$coupon_code,$unlimited_coupon,$display_at_checkout,$status,$start_date,$end_date,$never_expired,
            $count,$discount,$minimum_order,$category,$categories,$collection,$collections,$customer,$product;
    public $apply_for = 'all-orders';
    public $discount_type = 'flat';
    protected $listeners = ['suggestion','unsetsuggestion','customer_suggestion','unset_customer_suggestion'];

    public $product_ids = [''];
    public $products = [];
    public $selected_products = [];
    public $suggesstion = false;
    
    public $customer_ids = [''];
    public $customers = [];
    public $selected_customers = [];
    public $customer_suggesstion = false;

    
    // Products

    public function updatedProduct(){
        if(!empty($this->product)){
            $this->products = Product::where('name', 'like', "%{$this->product}%")->limit(5)->get();
        }
    }
    
    public function suggestion(){
        $this->suggesstion = true;
    }
    
    public function unsetsuggestion(){
        $this->suggesstion = false;
    }    
    
    public function addProduct($product_id){
        array_push($this->product_ids, $product_id);
        $this->selected_products = Product::Find($this->product_ids);
    }

    public function removeProduct($product_id){
        $this->product_ids = array_diff($this->product_ids, [$product_id]);
        $this->selected_products = Product::Find($this->product_ids);
    }

    // Customers

    public function updatedCustomer(){
        if(!empty($this->customer)){
            $this->customers = Customer::where('name', 'like', "%{$this->customer}%")->limit(5)->get();
        }
    }
    
    public function customer_suggestion(){
        $this->customer_suggesstion = true;
    }
    
    public function unset_customer_suggestion(){
        $this->customer_suggesstion = false;
    }
    
    public function addCustomer($customer_id){
        array_push($this->customer_ids, $customer_id);
        $this->selected_customers = Customer::Find($this->customer_ids);
    }

    public function removeCustomer($customer_id){
        $this->customer_ids = array_diff($this->customer_ids, [$customer_id]);
        $this->selected_customers = Customer::Find($this->customer_ids);
    }

    public function updatedNeverExpired(){
        if($this->never_expired=='yes'){
            $this->end_date = '';
        }
    }
    

    public function GenerateCouponCode(){
        $this->coupon_code = \Str::random(12);
    }

    public function store()
    {

        if(count($this->customer_ids)>1 && empty($this->customer_ids[0])){
            unset($this->customer_ids[0]);
        } 
        if(count($this->product_ids)>1 && empty($this->product_ids[0])){
            unset($this->product_ids[0]);
        }

         $rules = [
            'coupon_code' => 'required|min:8|max:12|unique:coupons,coupon_code,'.$this->coupon_id.',id,deleted_at,NULL',
            'status' => 'required',
            'discount_type' => 'required',            
            'category' => 'required_if:apply_for,category',
            'collection' => 'required_if:apply_for,collection',
            'customer_ids.*' => 'required_if:apply_for,customer',
            'product_ids.*' => 'required_if:apply_for,product',
         ];
         
         if($this->discount_type!='free_shipping'){
            $rules['discount'] = ($this->discount_type=='flat')?'required|numeric|min:1':'required|numeric|max:99|min:1';
         }
         if($this->unlimited_coupon!='yes'){
            $rules['count'] = 'required|numeric';
         }
         if($this->never_expired!='yes'){
            $rules['end_date'] = 'required';
         }
         if($this->apply_for=='minimum-order'){
            $rules['minimum_order'] = 'required|min:1|numeric';
         }
         $this->validate($rules);
         
         $validateData['coupon_code'] = $this->coupon_code;
         $validateData['unlimited_coupon'] = ($this->unlimited_coupon=='yes')?'yes':'no';
         $validateData['count'] = ($this->unlimited_coupon!='yes')?$this->count:0;
         $validateData['display_at_checkout'] = ($this->display_at_checkout=='yes')?'yes':'no';
         $validateData['discount'] = ($this->discount_type!='free_shipping')?$this->discount:0;
         $validateData['discount_type'] = $this->discount_type; 
         $validateData['apply_for'] = $this->apply_for;
         $validateData['status'] = $this->status;
         $apply_for_ids = ($this->apply_for=='product')? ','.implode(',',$this->product_ids).',' :
         (($this->apply_for=='customer')? ','.implode(',',$this->customer_ids).',' :
         (($this->apply_for=='category')?$this->category:
         (($this->apply_for=='collection')?$this->collection:'')));
         $validateData['apply_for_ids'] = $apply_for_ids;
         $validateData['minimum_order'] = ($this->apply_for=='minimum-order')?$this->minimum_order:0;
         $validateData['never_expired'] = ($this->never_expired=='yes')?'yes':'no';
         $validateData['start_date'] = Carbon::parse($this->start_date)->format('Y-m-d H:i');
         $validateData['end_date'] = ($this->never_expired=='yes')?null:Carbon::parse($this->end_date)->format('Y-m-d H:i');
        //  dd($validateData)
         $coupon = Coupon::updateOrCreate(
            ['id' => $this->coupon_id],
            $validateData
        );
        
        session()->flash('message', 'Coupon successfully saved.');
        
        return redirect()->to('admin/coupon');
         
    }
    
    public function mount($coupon_id){  
        if(!empty($coupon_id)){
            $coupon = Coupon::find($coupon_id);
            $this->coupon_code = $coupon->coupon_code;
            $this->display_at_checkout = ($coupon->display_at_checkout=='yes')?'yes':'';
            $this->unlimited_coupon = ($coupon->unlimited_coupon=='yes')?'yes':'';
            $this->count = $coupon->count;
            $this->discount_type = $coupon->discount_type;
            $this->discount = ($coupon->discount!='0')?$coupon->discount:'';
            $this->status = $coupon->status;
            $this->start_date = Carbon::parse($coupon->start_date)->format('d-m-Y H:i');
            $this->end_date = (!empty($coupon->end_date))?Carbon::parse($coupon->end_date)->format('d-m-Y H:i'):'';
            $this->never_expired = ($coupon->never_expired=='yes')?'yes':'';
            $this->apply_for = $coupon->apply_for;
            $this->category = ($coupon->apply_for=='category')?$coupon->apply_for_ids:null;
            $this->collection = ($coupon->apply_for=='collection')?$coupon->apply_for_ids:null;
            $this->customer_ids = ($coupon->apply_for=='customer')?array_values(array_filter(explode(',',$coupon->apply_for_ids))):[''];
            $this->product_ids = ($coupon->apply_for=='product')?array_values(array_filter(explode(',',$coupon->apply_for_ids))):[''];
            $this->minimum_order = $coupon->minimum_order;
            $this->selected_customers = Customer::Find($this->customer_ids);
            $this->selected_products = Product::Find($this->product_ids);
        }

        $this->customers = Customer::limit(5)->get();
        $this->products = Product::limit(5)->get();
        $this->categories = Category::whereNULL('parent_id')->orderBy('sort','asc')->whereStatus('active')->get();
        $this->collections = Collection::get();
        $this->today = Carbon::now()->format('d-m-Y H:i');
    }

    public function render()
    {
        return view('livewire.coupon.create');
    }
}
