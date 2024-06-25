<?php

namespace App\Http\Livewire\Ecommerce\Product;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Review;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use DB;

class Reviews extends Component
{
    
    public $product_id,$purchased_product,$is_reviewed,$rating,$title,$content;
    public $review_form = false;
    protected $listeners = ['ReportReview'];
    use WithPagination;

    public function ReviewForm(){
        
        $this->review_form = true;
    }

    public function RateProduct(){
        $validatedData = $this->validate([
            'rating' => 'required',
            'title' => 'required|string|min:3|max:180',
            'content' => 'required|string|min:3|max:255',
        ], [
            'rating.required' => 'Rating is required',
            'title.required'=> 'Please enter your review title',
            'title.min' => 'Title must be at least 3 characters',
            'title.max' => 'Title must be less than 30 characters.',
            'content.required'=> 'Please describe your review',
            'content.min' => 'Description must be at least 3 characters',
            'content.max' => 'Description must be less than 30 characters.',
        ]);
        $validatedData['commends'] = $validatedData['content'];
        unset($validatedData['content']);
        $validatedData['user_id'] = auth()->user()->id??0;
        $validatedData['product_id'] = $this->product_id;
        Review::create($validatedData);
        $results = Review::select(
            DB::raw('SUM(rating) as sum_of_ratings'),
            DB::raw('COUNT(id) as count_of_reviews')
        )->whereProductId($this->product_id)->first();
        $avg_rating = ($results->sum_of_ratings!=0)?round($results->sum_of_ratings/$results->count_of_reviews):0;
        Product::where('id',$this->product_id)->update(['rating'=>$avg_rating]);
        $this->is_reviewed = Review::whereUserId(auth()->user()->id)->whereProductId($this->product_id)->first();
        $this->review_form = false;
    }

    public function ReportReview($review_id){

        if(\Auth::check()){

            $reported = Review::where('id',$review_id)->where('report_user_ids', 'REGEXP', auth()->user()->id)->first();
            
            if(!isset($reported)){
    
                $report = Review::find($review_id);
                $report_user_ids = array_filter(array_values(explode(',',$report->report_user_ids)));
                array_push($report_user_ids, auth()->user()->id);
                $report->report_user_ids = ','.implode(',', $report_user_ids).',';
                $report->save();
            }
            
        }
        
    }

    public function mount($id){
        $this->product_id = $id;
                              
        $user_id = auth()->user()->id??0;

        $order_item = OrderItem::whereHas('orders',function($q) use($user_id){
            $q->whereUserId($user_id);
        })->whereProductId($id)->first();

        $this->is_reviewed  = Review::whereUserId($user_id)->whereProductId($id)->first();
        $this->purchased_product = isset($order_item)?'yes':'no';
    }

    public function render()
    {
        $user_id = auth()->user()->id??0;
        $rating_count = Review::whereProductId($this->product_id)->count();
        $rating_sum = Review::whereProductId($this->product_id)->sum('rating');        
        $this->review = ($rating_count!=0)?round($rating_sum/$rating_count):0;
        $this->review_count = $rating_count;
        $all_reviews = Review::where('user_id','!=',$user_id)->whereProductId($this->product_id)->paginate(10)->onEachSide(1);

        $this->reviews = array_map(function ($review) {
            $review['name'] = User::where('id',$review['user_id'])->pluck('name')->first();
            $review['is_reported'] = Review::where('id',$review['id'])->where('report_user_ids', 'REGEXP', auth()->user()->id??0)->pluck('id')->first();
            return $review;
        }, $all_reviews->toArray()['data']);
        return view('livewire.ecommerce.product.reviews',compact('all_reviews'));
    }

    public function paginationView()
    {
        return 'custom-pagination-links-view';
    }

}
