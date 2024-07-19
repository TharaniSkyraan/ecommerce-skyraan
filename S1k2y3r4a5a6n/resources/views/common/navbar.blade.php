<section class="top-nav py-2">
    <div class="container">
        <div class="row ">
            <div class="col-4 d-none d-lg-block">
                <div class="d-flex gap-2 align-items-center">
                    <h6 class="text-white h-sms">Need help ?</h6><h6 class="text-dark h-sms">+91 {{$siteSetting->phone}}</h6>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4 col-xl-4 col-xxl-4 col-12">
                <!-- <div class="d-flex justify-content-center align-items-center ">
                    <div id="top_nav_carousel" class="owl-carousel">
                        <div class="item"><h6 class="text-white text-center fw-normal h-sms">deal of the day ! : Nature beauty collection</h6></div>
                        <div class="item"><h6 class="text-white text-center fw-normal h-sms">deal of the day ! : Nature beauty collection</h6></div>
                    </div>
                </div> -->
            </div>
            <div class="col-4 d-none d-lg-block">
                <div class="d-flex gap-4 justify-content-end align-items-center">
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
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @livewire('ecommerce.layout.view-canvas-cart')        
    </div>

    @if(!Auth::check())
        <!-- Sign in Modal -->
        <div class="modal  fade" id="signin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="text-end ">
                    <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                </div>
                <div class="modal-content">
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
                <div class="text-end ">
                    <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                </div>
                <div class="modal-content">
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
                <div class="text-end ">
                    <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
                </div>
                <div class="modal-content">
                    <div class="modal-bodys">
                        @livewire('ecommerce.user.forgot-password')  
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Track -->
    <!-- <div class="modal fade" id="tracking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="text-end">
                <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
            </div>
            <div class="modal-content">
                <div class="modal-bodys p-3">
                    <div class="text-center head">
                        <h4 class="">Track my order</h4>
                        <small class="text-secondary opacity-50">Let's see where's your package now!</small>
                    </div>
                    <hr>
                    <div class="container-fluid">
                        <div class="tabs_nd_mbls">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row ">
                                        <div class="col-4 text-center">
                                            <div class="card border-0">
                                                <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                            </div>
                                        </div>
                                        <div class="col-8 align-self-center">
                                            <div class="d-flex gap-2 align-items-center">
                                                <small class="fw-bold">Jaggery powder</small>
                                            </div>
                                            <small class="text-secondary opacity-50 py-1">Size : 100 g</small>

                                            <div class="d-flex gap-1 py-1">
                                                <small>Order ID : <small class="fw-bold"> SKY115</small></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 order-track align-self-center">
                                    <div class="pe-3">
                                        <button type="button" class="btn fw-bold w-100" data-bs-toggle="modal" data-bs-target="#tracking"><small>View Details</small></button>
                                    </div>
                                    <div class=" pt-2 pe-3">
                                        <button type="button" class="btn fw-bold w-100"><small>Cancel order</small></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs_nd_mbl">
                            <div class="row ">
                                <div class="col-10 px-0">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-4 text-center ps-0">
                                                <div class="card border-0">
                                                    <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                </div>
                                            </div>
                                            <div class="col-8 align-self-center pe-0">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <small class="fw-bold">Jaggery powder</small>
                                                    <div class=" d-flex align-items-center">
                                                        <img src="{{asset('asset/home/exclamation.png')}}" alt="" class="detail_icon"><small class="text-secondary opacity-50">Details</small>
                                                    </div>
                                                </div>
                                                <small class="text-secondary opacity-50 py-1">Size : 100 g</small>

                                                <div class="d-flex gap-1 py-1">
                                                    <small>Order ID : <small class="fw-bold"> SKY115</small></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-2 order-track d-flex justify-content-end px-0">
                                    <div class="dropdown">
                                        <img class=" dropdown-toggle icons-menu" src="{{asset('asset/home/icons-menu.png')}}" alt="menu" data-bs-toggle="dropdown">
                                        <ul class="dropdown-menu p-2" aria-labelledby="navbarDropdownMenuLink">
                                            <li data-bs-toggle="modal" data-bs-target="#tracking">Track my order</li>
                                            <li data-bs-toggle="modal" data-bs-target="#cancel-order">Cancel order</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <div class="row pt-4">
                            <div class="col-12 hh-grayBox">
                                <div class="d-flex justify-content-between py-3">
                                    <div class="order-tracking completed px-0">
                                    <span class="outer-round"><span class="is-complete"></span></span>
                                        <h6 class="h-sms fw-normal text-secondary">Order</h6>
                                    </div>
                                    <div class="order-tracking completed px-0">
                                    <span class="outer-round"><span class="is-complete"></span></span>
                                        <h6 class="h-sms fw-normal text-secondary">Shipped</h6>
                                    </div>
                                    <div class="order-tracking px-0">
                                    <span class="outer-round"><span class="is-complete"></span></span>
                                        <h6 class="h-sms fw-normal text-secondary">Out of delivery</h6>
                                    </div>
                                    <div class="order-tracking px-0">
                                    <span class="outer-round"><span class="is-complete"></span></span>
                                        <h6 class="h-sms fw-normal text-secondary">Delivered</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-2">
                        <small class="fw-bold">Shipped to</small>
                    </div>
                    <div>
                        <div class="card p-2 rounded-0">
                            <div class="row">
                                <div class="col-10">
                                    <small class="fw-bold">Arunkumar A</small><br>
                                    <small class="py-1">No. 37, Santhome High Road, Chennai, 600004, India</small><br>
                                    <small>+91 99445 58867</small>
                                </div>
                                <div class="col-2 text-end ps-0">
                                    <a href="" class="text-decoration-underline red "><small>Edit</small></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>   
        </div>   
    </div> -->


   <!-- verify otp -->
    <div class="modal fade" id="verify-otp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="text-end">
                <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
            </div>
            <div class="modal-content">
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
                    <a href="{{ route('ecommerce.cart') }}"><img src="{{asset('asset/home/cart.svg')}}" alt=""></a>
                </div>
                @if(Auth::check())
                    <div class="vr"></div>
                    <div class="col text-center">
                        <a href="{{ route('ecommerce.wish-lists') }}"><img src="{{asset('asset/home/like-nav.svg')}}" alt="wishlist" class="like_nav"></a>
                    </div>
                @endif
                <div class="vr"></div>
                <div class="col text-center">
                    <a href="{{ route('ecommerce.home') }}"><img src="{{asset('asset/home/home.svg')}}" alt=""></a>
                </div>
                <div class="vr"></div>
                @if(!Auth::check())
                <div class="col text-center">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#signin"><img src="{{asset('asset/home/login.svg')}}" alt="" class="login_nav"></a>
                </div>
                @else                
                <div class="col text-center">
                    <a href="{{ route('ecommerce.account') }}"><img src="{{asset('asset/home/login.svg')}}" alt="login"  class="login_nav"></a>
                </div>
                @endif
                <div class="vr"></div>
                <div class="col text-center">
                    <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
                        <!-- <img src="{{asset('asset/home/icons8-search-26.png')}}" alt=""> -->
                        <i class="bi bi-search"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>  
</section>
