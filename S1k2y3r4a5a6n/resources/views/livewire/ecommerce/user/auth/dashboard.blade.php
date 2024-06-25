<div>
    <section class="userdashboard">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12 select-li ps-xl-3 ps-lg-3 ps-md-2 ps-sm-0 ps-0 pe-xl-3 pe-lg-3 pe-md-2 pe-sm-0 pe-0 pb-3">
                    @include('ecommerce.user.auth.sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12 detail-li pb-3">
                    <div class="card p-xl-4 p-lg-4 p-md-4 p-sm-4 p-1 ss">
                        <div class="d-flex justify-content-between ">
                            <div>
                                <h3 class="fw-bold">Hello {{auth()->user()->name}} !</h3>
                                <h6 class="pt-2 dtl fw-normal h-sms">Here’s what’s going on in your account.</h6>
                            </div>
                            <div>
                                <a href="{{ route('ecommerce.account') }}" class="btn px-xl-4 px-lg-4 px-sm-4 px-md-4 px-2"><h6 class="text-nowrap">Account settings</h6></a>
                            </div>
                        </div>
                        @if(count($order_items)==0)
                            <div class="py-4">
                                <div class="card border-0 p-xl-4 p-lg-4 p-md-4 p-sm-4 p-2 text-center or-li">
                                    <h5 class="fw-bold">Your Orders</h5>
                                    <h6 class="py-3 fw-normal h-sms">It looks like you didn't order any product past 3 months.</h6>
                                    <div  class="text-center">
                                        <button type="button" class="btn text-white px-xl-4 px-lg-4 px-sm-4 px-md-4 px-3">Click here to purchase</button>
                                    </div>
                                </div> 
                            </div>
                        @else
                            <section class="item-list pt-4">
                                <div id="order-list" class="owl-carousel ">
                                @foreach($order_items as $order)
                                    <div class="owl-slide ">
                                        <div class="container-fluid">
                                            <div class="row pb-5">
                                                <div class="col-12">
                                                    <div class="card round-1 p-1 cursor">
                                                        <div class="row pt-1  position-absolute w-100 ">
                                                            <div class="col-12 d-flex justify-content-end pe-0 align-self-center reviews-div">
                                                                <!-- <div class=" rounded-circle bg-white ">
                                                                    <img src="{{asset('asset/home/like-filled.svg')}}" alt="like" class="like_img" >
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                        @if(!empty($order['image1']))
                                                            <div class="text-center position-relative order-img">
                                                                <img src="{{ $order['image1'] }}" alt="Image 1" class=" item-image">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="price_info py-2 px-3">
                                                        <h6 class="text-dark fw-bold align-self-center h-sms pb-1">{{$order->product_name}}</h6>
                                                        <div class="">
                                                            <small>Order ID : <small class="fw-bold"> </small>{{ $order->order_code }}</small>
                                                        </div>
                                                        <div class="d-flex gap-xl-2 gap-lg-2 gap-md-2 gap-sm-2 gap-1 align-items-center">
                                                            <h6 class="buy-color h-sms cursor">order detail</h6>
                                                            <img src="{{asset('asset/home/right-ar-clr.png')}}" alt="arrow">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach                              
                                </div>
                            </section>
                        @endif
                        <div class="recommended-li">
                            <h4 class="fw-bold pb-3">Recommended for you</h4>
                            <div class="carousel-wrap">
                                <div class="owl-carousel" id="dashboard-carousel">
                                @foreach($recommeded_products as $product)
                                    <div class="item"><img src="{{ $product->image1 }}"></div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>