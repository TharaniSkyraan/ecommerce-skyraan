<section class="top-nav py-2">
    <div class="container">
        <div class="row ">
            <div class="col-3 d-none d-lg-block">
                <div class="d-flex gap-2 align-items-center">
                    <h6 class="text-white h-sms">Need help ?</h6><h6 class="text-dark h-sms">+91 {{$siteSetting->phone}} </h6>
                </div>
            </div>
            <div class="col-sm-12 col-lg-3 col-xl-3 col-xxl-3 col-12">
                <!-- <div class="d-flex justify-content-center align-items-center ">
                    <div id="top_nav_carousel" class="owl-carousel">
                        <div class="item"><h6 class="text-white text-center fw-normal h-sms">deal of the day ! : Nature beauty collection</h6></div>
                        <div class="item"><h6 class="text-white text-center fw-normal h-sms">deal of the day ! : Nature beauty collection</h6></div>
                    </div>
                </div> -->
            </div>
            <div class="col-6 d-none d-lg-block">
                <div class="d-flex gap-4 justify-content-end align-items-center">
                    <h6 class="text-white h-sms"> <i class='bi bi-geo-alt'></i> Delivering to - {{ $zone_data['city']}} {{ $zone_data['postal_code']}} {{ $zone_data['warehouse_ids']}} {{ $zone_data['zone_id']}} </h6>
                    <a href="{{url('/aboutus')}}"><h6 class="text-white h-sms">About Us</h6></a>
                    <a href="{{url('/contactus')}}"><h6 class="text-white h-sms">Contact Us</h6></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="search_menu_nav">
    @livewire('ecommerce.layout.nav')
    
    <!-- Mobile Responsive -->
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
        <div class="offcanvas-header d-flex justify-content-end pb-0">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @livewire('ecommerce.layout.product-search')
    </div>

    <!-- Add to cart offcanvas left -->
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title fw-bold" id="offcanvasRightLabel">My Shopping Cart</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @livewire('ecommerce.layout.view-canvas-cart')        
    </div>

    <!-- Sign in Modal -->
    @if(!Auth::check())
        <div class="modal  fade" id="signin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="text-end ">
                        <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                    </div>
                    <div class="modal-bodys">
                        @livewire('ecommerce.user.login')  
                        <!-- <div class="container-fluid">
                            <div class="row  eq-height">
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12 py-4 sign-in d-flex align-items-center justify-content-center">
                                    <h4 class="fw-bold text-dark text-center text-white">SKYRAAN</h4>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12 py-xl-5 py-lg-5 py-sm-5 py-md-5 py-2 align-self-center">
                                    <div class="px-xl-3 px-lg-3 px-sm-3 px-md-3 px-2">
                                        <h5 class="fw-bold">Sign in</h5>
                                        <div class="py-3">
                                        <input type="text" class="form-control" placeholder="Email">
                                        </div>
                                        <div class="pb-3">
                                        <input type="text" class="form-control" placeholder="Password">
                                        </div>
                                        <a href="" class="btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-100">
                                            <h5>sign in</h5>
                                        </a>
                                        <div class="pt-2 d-flex align-items-center gap-1 justify-content-between">
                                            <h6 class="h-sm fw-normal cursor" data-bs-toggle="modal" data-bs-target="#forgotpassword">Forgot password</h6>
                                            <div class="d-flex align-items-center gap-1">
                                            <h6 class="text-center py-1 h-sm fw-normal">New customers? <h6 class="click-here h-sm" data-bs-toggle="modal" data-bs-target="#signup">start here</h6></h6> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Signup -->
        <div class="modal fade" id="signup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="text-end ">
                        <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                    </div>
                    <div class="modal-bodys">
                        @livewire('ecommerce.user.signup')  
                    </div>
                </div>
            </div>
        </div>

        <!-- Verify Otp -->
        <div class="modal fade" id="numberverify" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-0">
                    <div class="modal-body p-2 px-5">
                        @livewire('ecommerce.user.verify-otp') 
                    </div>
                </div>
            </div>
        </div>

        <!-- forgot password -->
        <div class="modal fade" id="forgotpassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="text-end ">
                        <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                    </div>
                    <div class="modal-bodys">
                        @livewire('ecommerce.user.forgot-password')  
                    </div>
                </div>
            </div>
        </div>
    @endif

   <!-- verify otp -->
    <div class="modal fade" id="verify-otp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="text-end">
                    <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                </div>
                <div class="modal-bodys p-5">
                    <div class="col-12 text-center pt-2">
                        <img src="" alt="">
                        <h6 class="fw-bold">Your request for cancellation has been submitted successfully</h6>
                        <div class="pt-4">
                        <button class="btn text-white" data-bs-toggle="modal" data-bs-target="#verify-otp">Submit request</button>
                        </div>
                    </div>
                </div>   
            </div>   
        </div>   
    </div>

    <!-- Static mobile view icons -->
    <div class="container-fluid static-icons fixed-bottom mbl-view tab-views">
        <div class="row">
            <div class="card d-flex flex-row py-3 border-0 rounded-0">
                <div class="col text-center">
                    <a href="{{ route('ecommerce.cart') }}"><img src="{{asset('asset/home/btm-cart.svg')}}" alt="" class="like_navs"></a>
                </div>
                @if(Auth::check())
                    <div class="vr"></div>
                    <div class="col text-center">
                        <a href="{{ route('ecommerce.wish-lists') }}"><img src="{{asset('asset/home/btm-like.svg')}}" alt="wishlist" class="like_nav"></a>
                    </div>
                @endif
                <div class="vr"></div>
                <div class="col text-center">
                    <a href="{{ route('ecommerce.home') }}"><img src="{{asset('asset/home/btm-home.svg')}}" alt=""></a>
                </div>
                <div class="vr"></div>
                @if(!Auth::check())
                <div class="col text-center">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#signin"><img src="{{asset('asset/home/btm-login.svg')}}" alt="" class="login_nav"></a>
                </div>
                @else                
                <div class="col text-center">
                    <a href="{{ route('ecommerce.account') }}"><img src="{{asset('asset/home/btm-login.svg')}}" alt="login"  class="login_nav"></a>
                </div>
                @endif
                <div class="vr"></div>
                <div class="col text-center">
                    <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
                        <img src="{{asset('asset/home/btm-search.svg')}}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>  
</section>
