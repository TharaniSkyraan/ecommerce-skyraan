<div class="card p-3">
    <div class="d-flex justify-content-between">
        <h5 class="fw-bold">Customer Reviews</h5>
        @if(!Auth::check())
        <div><h6 class="hide-reviews h-sms fw-normal text-end">Write a review</h6>
        <span class="hide-review h-sms">*Login to write a review</span></div>
        @endif
        @if(Auth::check() && !($review_form) && !isset($is_reviewed) && $purchased_product!='yes')<h6 class="write-review" wire:click.prevent="ReviewForm">Write a review</h6> @endif
    </div>
    <div class="d-flex gap-xl-1 gap-lg-1 gap-2 align-items-center py-2">
        @if($review==0)
        <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
        @elseif($review==1)
        <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
        @elseif($review==2)
        <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
        @elseif($review==3)
        <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
        @elseif($review==4)
        <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
        @elseif($review==5)
        <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
        @endif
        <h6 class="fw-normal h-sms">Based on {{$review_count}} Reviews</h6>
    </div>
    <hr>
    @if(Auth::check())
        @if($purchased_product=='yes' && !isset($is_reviewed))
            <div class="review-form">
                <form>
                    <h6 class="fw-normal">Ratings</h6>
                    <!-- <div class="d-flex  gap-1 align-items-center py-2 ratings" wire:ignore>
                        <img src="{{ asset('asset/home/star-img.svg') }}" alt="star" class="star cursor">
                        <img src="{{ asset('asset/home/star-img.svg') }}" alt="star" class="star cursor">
                        <img src="{{ asset('asset/home/star-img.svg') }}" alt="star" class="star cursor">
                        <img src="{{ asset('asset/home/star-img.svg') }}" alt="star" class="star cursor">
                        <img src="{{ asset('asset/home/star-img.svg') }}" alt="star" class="star cursor">
                    </div> -->
                    <div class="d-flex gap-1 align-items-center py-2 ratings" wire:ignore>
                        <svg class="star" data-rating="1" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512" fill="none">
                            <path d="M256 0L316.433 195.567H512L353.783 316.433L414.217 512L256 391.133L97.7831 512L158.217 316.433L0 195.567H195.567L256 0Z" fill="#D6D6D6"/>
                        </svg>
                        <svg class="star" data-rating="2" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512" fill="none">
                            <path d="M256 0L316.433 195.567H512L353.783 316.433L414.217 512L256 391.133L97.7831 512L158.217 316.433L0 195.567H195.567L256 0Z" fill="#D6D6D6"/>
                        </svg>
                        <svg class="star" data-rating="3" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512" fill="none">
                            <path d="M256 0L316.433 195.567H512L353.783 316.433L414.217 512L256 391.133L97.7831 512L158.217 316.433L0 195.567H195.567L256 0Z" fill="#D6D6D6"/>
                        </svg>
                        <svg class="star" data-rating="4" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512" fill="none">
                            <path d="M256 0L316.433 195.567H512L353.783 316.433L414.217 512L256 391.133L97.7831 512L158.217 316.433L0 195.567H195.567L256 0Z" fill="#D6D6D6"/>
                        </svg>
                        <svg class="star" data-rating="5" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512" fill="none">
                            <path d="M256 0L316.433 195.567H512L353.783 316.433L414.217 512L256 391.133L97.7831 512L158.217 316.433L0 195.567H195.567L256 0Z" fill="#D6D6D6"/>
                        </svg>
                    </div>
                    @error('rating') <span class="error"> {{$message}}</span> @endif
                    <div class="mb-3">
                        <label for="reviewTitle" class="form-label">Review title</label>
                        <input type="title" class="form-control rounded-0 fst-italic" id="reviewTitle" aria-describedby="emailHelp" placeholder="Give your title" wire:model="title">
                    </div>
                    @error('title') <span class="error"> {{$message}}</span> @endif
                    <div class="mb-3">
                        <label for="review_description" class="form-label">Body of review <span class="text-secondary opacity-50 h-sms">(1500)</span></label>
                        <textarea class="form-control rounded-0 fst-italic" placeholder="Leave a comment here" id="review_description" placeholder="Enter your comments here" wire:model="content"></textarea>
                    </div>
                    @error('content') <span class="error"> {{$message}}</span> @endif
                    <div class="text-end">
                        <button type="button" class="btn px-5" wire:click.prevent="RateProduct"><h5 class="text-white py-1 fw-normal">submit</h5></button>
                    </div>
                </form>
            </div>
        @else
        <div class="review-form {{ ($review_form)?'':'d-none' }}">
            <div class="text-center">
                <h4 class="price">You didn't purchase this item !</h4>
                <div class="py-1">
                    <span class="text-secondary opacity-75 h-sm fst-italic">For giving review for this product, you must purchase this item</span>
                </div>
            </div>
        </div>
        @endif
    @endif

    @if(isset($is_reviewed))
        <div class="d-flex gap-xl-1 gap-lg-1 gap-1 align-items-center py-2">
            @if($is_reviewed->rating==0)
            <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
            @elseif($is_reviewed->rating==1)
            <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
            @elseif($is_reviewed->rating==2)
            <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
            @elseif($is_reviewed->rating==3)
            <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
            @elseif($is_reviewed->rating==4)
            <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
            @elseif($is_reviewed->rating==5)
            <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
            @endif
        </div>
        <h5 class="fw-bold">{{$is_reviewed->title}}</h5>
        
        <div class="d-flex">
            <h6 class="text-secondary py-1 h-sms">{{ auth()->user()->name }} <small class="text-secondary opacity-50">on</small> {{ \Carbon\Carbon::parse($is_reviewed->updated_at)->format('M d, Y') }} </h6>
            <label class="card px-2 py-1 mx-1 border-0 activecls"><small class="h-sm">You</small></label>
        </div>
        <div class="d-flex justify-content-between">
            <h6 class="text-secondary opacity-50 h-sms fw-normal">{{$is_reviewed->commends}}</h6>
        </div>
    @endif
    
    @foreach($reviews as $key => $rev)
        <div class="d-flex gap-xl-1 gap-lg-1 gap-1 align-items-center py-2">
            @if($rev['rating']==0)
            <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star">
            @elseif($rev['rating']==1)
            <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
            @elseif($rev['rating']==2)
            <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
            @elseif($rev['rating']==3)
            <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
            @elseif($rev['rating']==4)
            <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
            @elseif($rev['rating']==5)
            <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
            @endif
        </div>
        <h5 class="fw-bold">{{$rev['title']}}</h5>
        <h6 class="text-secondary py-1 h-sms">{{ ucwords($rev['name'])}} <small class="text-secondary opacity-50">on</small> {{ \Carbon\Carbon::parse($rev['updated_at'])->format('M d, Y') }} </h6>
        <div class="d-flex justify-content-between">
            <h6 class="text-secondary opacity-50 h-sms fw-normal">{{$rev['commends']}}</h6>
            @if($rev['is_reported'])
                <h6 class="text-secondary h-sms fw-normal">This review has been reported</h6>
            @else
                <h6 class="text-secondary h-sms fw-normal ReportReview cursor" data-id="{{$rev['id']}}">Report this review</h6>
            @endif
        </div>
        @if($key < (count($all_reviews)-1) || $all_reviews->hasPages()) <hr> @endif
    @endforeach
    <div class="align-self-center mt-3">
    {{ $all_reviews->links('custom-pagination-links') }}
    </div>
</div>

@push('scripts')
<script>
    //  ratings
    $(document).ready(function() {
        $('.ratings .star').click(function() {
            var $stars = $('.ratings .star');
            var index = $stars.index(this);

            $stars.each(function(i) {
                if (i <= index) {
                    $(this).attr('src', '../../asset/home/star-img-filled.svg');
                } else {
                    $(this).attr('src', '../../asset/home/star-img.svg');
                }
            });
            @this.set('rating', (index+1));

        });
    });
    
    $(document).ready(function() {
        $(document).on('click', '.ReportReview', function () {
            if (confirm('Are you sure you want to report this review as inappropriate?')) {
                var id=$(this).attr('data-id'); 
                Livewire.emit('ReportReview',id);
            }
        });
    });
</script>
@endpush