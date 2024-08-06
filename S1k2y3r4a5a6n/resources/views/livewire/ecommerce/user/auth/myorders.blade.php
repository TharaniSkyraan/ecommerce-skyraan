<div>
    <section class="userdashboard">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 select-li ps-xl-3 ps-lg-3 ps-md-2 ps-sm-0 ps-0 pe-xl-3 pe-lg-3 pe-md-2 pe-sm-0 pe-0 pb-3">
                    @include('ecommerce.user.auth.sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 order-li pb-3">
                    <div class="card p-xl-4 p-lg-4 p-md-4 p-sm-2 p-0 kdk">
                        <div class="d-flex gap-xl-5 gap-lg-5 gap-md-5 gap-sm-5 gap-4 justify-content-start pb-4 tab-head">
                            <h6 class="activated" data-tab="tab1">Orders</h6>
                            <h6 class="" data-tab="tab2">Buy it again</h6>
                            <h6 class="" data-tab="tab3">Order history</h6>
                        </div>
                        <div id="tab1" class="tab-content active">
                            <div class="pb-3">
                                <div class="card pt-2 tabs_nd_mbls">
                                    <div class="container-fluid">
                                        <div class="row pb-3 kfk">
                                            <div class="col-xl-9 col-lg-8 col-sm-8 col-md-8 col-12">
                                                <div class="row">
                                                    <div class="col-4 text-center ps-0">
                                                        <div class="card border-0">
                                                            <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                        </div>
                                                    </div>
                                                    <div class="col-8 align-self-center">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                            <div class=" d-flex align-items-center">
                                                                <img src="{{asset('asset/home/exclamation.png')}}" alt="" class="detail_icon"><small class="text-secondary opacity-50 h-sm">Details</small>
                                                            </div>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-2 h-sms">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 h-sm">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-2">
                                                            <h6>Order ID : <h6 class="fw-bold"> SKY115</h6></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-4 col-sm-4 col-md-4 col-12 order-track align-self-center">
                                                <div class="pe-3 sys-view">
                                                    <button type="button" class="btn px-4 w-100 h-sms" data-bs-toggle="modal" data-bs-target="#tracking">Track my order</button>
                                                </div>
                                                <div class=" pt-3 pe-3 sys-view ">
                                                    <button type="button" class="btn px-4 w-100 h-sms" data-bs-toggle="modal" data-bs-target="#cancel-order">Cancel order</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid">
                                        <div class="row p-1 order-summary-bg">
                                            <div class="col-3 text-center  border-end border-white ps-0 pe-0"><small class="h-sm">Ordered on <small class="fw-bold h-sm">January 12, 2024</small></small></div>
                                            <div class="col-4 text-center  border-end border-white ps-0 pe-0"><small class="h-sm">Delivered to <small class="fw-bold h-sm">Arun kumar S <img src="{{asset('asset/home/edit.svg')}}" alt="edit"></small></small></div>
                                            <div class="col-2 text-center  border-end border-white ps-0 pe-0"><small class="h-sm">Status<small class="fw-bold h-sm"> Shipped</small></small></div>
                                            <div class="col-1 text-center  border-end border-white ps-0 pe-0"><small class="fw-bold ps-1 h-sm">Invoice</small></div>
                                            <div class="col-2 text-center ps-0 pe-0"><small>Total<small class="fw-bold ps-1 h-sm">{{ $ip_data->currency_symbol??'₹' }} 299.00</small></small></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-2 tabs_nd_mbl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="card border-0">
                                                    <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                </div>
                                            </div>
                                            <div class="col-9 ">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                            <div class=" d-flex align-items-center">
                                                                <img src="{{asset('asset/home/exclamation.png')}}" alt="" class="detail_icon"><small class="text-secondary opacity-50">Details</small>
                                                            </div>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 ">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <h6>Order ID : <h6 class="fw-bold"> SKY115</h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown">
                                                        <img class=" dropdown-toggle icons-menu" src="{{asset('asset/home/icons-menu.svg')}}" alt="menu" data-bs-toggle="dropdown">
                                                        <ul class="dropdown-menu p-2" aria-labelledby="navbarDropdownMenuLink">
                                                            <li data-bs-toggle="modal" data-bs-target="#tracking">Track my order</li>
                                                            <li data-bs-toggle="modal" data-bs-target="#cancel-order">Cancel order</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between py-2">
                                                    <small>Total<small class="fw-bold ps-1">{{ $ip_data->currency_symbol??'₹' }} 299.00</small></small>
                                                    <small>Status<small class="fw-bold price"> Shipped</small></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-summary-bg d-flex px-2 py-1 justify-content-between align-items-center">
                                        <small>Ordered on <small class="fw-bold">January 12, 2024</small></small>
                                        <small>Delivered to <small class="fw-bold">Arun kumar S <img src="{{asset('asset/home/edit.svg')}}" alt="edit"></small></small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-self-center">
                                <nav aria-label="Page navigation example text-center">
                                    <ul class="pagination text-center">
                                        <li class="arrow d-flex align-items-center">
                                            <a class="" aria-label="Previous">
                                                <span class="text-center" aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                            <li class="page-item"><a class="page-link active">1</a></li>
                                            <li class="page-item"><a class="page-link">2</a></li>
                                            <li class="page-item"><a class="page-link">3</a></li>
                                        <li class="arrow d-flex align-items-center">
                                            <a class="" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav> 
                            </div>
                        </div>
                        <div id="tab2" class="tab-content ">
                            <div class="pb-3">
                                <div class="card pt-2 tabs_nd_mbls">
                                    <div class="container-fluid">
                                        <div class="row pb-3 px-3">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-4 text-center">
                                                        <div class="card border-0">
                                                            <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                        </div>
                                                    </div>
                                                    <div class="col-8 align-self-center">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1 h-sms fw-normal">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 h-sm">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <h6 class="h-sms">Order ID : <h6 class="fw-bold h-sms"> SKY115</h6></h6>
                                                        </div>
                                                        <div class="py-1">
                                                            <h6 class="h-sms fw-normal">Last buy : January 10, 2024</h6>
                                                        </div>
                                                        <div class="py-2">
                                                            <div class="card p-1 remove-card">
                                                                <div class="d-flex justify-content-center align-self-center gap-1">
                                                                    <img src="{{asset('asset/home/delete.png')}}" alt="delete" class="remove">
                                                                    <small class="h-sm">Remove form list</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 align-self-center ">
                                                <div class="qty-container d-flex align-items-center justify-content-center border p-1 rounded-1  text-dark">
                                                <div class="col text-center qty-btn-minus"><span>-</span></div>
                                                <div class="vr"></div>
                                                <div class="col text-center"><span class="input-qty h-sms">1</span></div>
                                                <div class="vr"></div>
                                                <div class="col text-center qty-btn-plus"><span>+</span></div>
                                
                                                </div>
                                            </div>
                                            <div class="col-4 order-track align-self-center ">
                                                <div class="d-flex gap-3 justify-content-end">
                                                    <h6 class="">Sub Total</h6>
                                                    <h6 class="b-ia">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                </div>
                                                <a href=""><h5 class="text-end pt-4 buy-color">Buy again</h5></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-2 tabs_nd_mbl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="card border-0">
                                                    <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                </div>
                                            </div>
                                            <div class="col-9 ">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 ">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <h6>Order ID : <h6 class="fw-bold"> SKY115</h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown">
                                                        <img class=" dropdown-toggle icons-menu" src="{{asset('asset/home/icons-menu.svg')}}" alt="menu" data-bs-toggle="dropdown">
                                                        <ul class="dropdown-menu p-2" aria-labelledby="navbarDropdownMenuLink">
                                                            <li data-bs-toggle="modal" data-bs-target="#tracking">Track my order</li>
                                                            <li data-bs-toggle="modal" data-bs-target="#cancel-order">Cancel order</li>
                                                            <li>Remove from list </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between py-2 align-items-center">
                                                    <small>Total<small class="fw-bold ps-1">{{ $ip_data->currency_symbol??'₹' }} 299.00</small></small>
                                                    <h4 class="fw-bold buy_now"> Buy again</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-self-center">
                                <nav aria-label="Page navigation example text-center">
                                    <ul class="pagination text-center">
                                        <li class="arrow d-flex align-items-center">
                                            <a class="" aria-label="Previous">
                                                <span class="text-center" aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                            <li class="page-item"><a class="page-link active">1</a></li>
                                            <li class="page-item"><a class="page-link">2</a></li>
                                            <li class="page-item"><a class="page-link">3</a></li>
                                        <li class="arrow">
                                            <a class="" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav> 
                            </div>
                        </div>
                        <div id="tab3" class="tab-content ">
                            <div class="pb-4">
                                <div class="card pt-2 tabs_nd_mbls">
                                    <div class="container-fluid">
                                        <div class="row pb-3 pe-2">
                                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-8">
                                                <div class="row">
                                                    <div class="col-4 text-center">
                                                        <div class="card border-0">
                                                            <img src="{{asset('asset/home/Mask Group 23212.png')}}" alt="" class="">
                                                        </div>
                                                    </div>
                                                    <div class="col-8 align-self-center">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1 h-sms fw-normal">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 h-sm">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <small>Order ID : <small class="fw-bold"> SKY115</small></small>
                                                        </div>
                                                        <div class="py-1 ">
                                                            <h6 class="h-sms">Last buy : January 10, 2024</h6>
                                                        </div>
                                                        <div class="py-2">
                                                            <div class="d-flex gap-2">
                                                                <h6>status : </h6>
                                                                <h6 class="buy-color">Delivered</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-4 order-track align-self-center">
                                                <div class="d-flex justify-content-end py-1">
                                                    <div class="card p-1 w-50">
                                                        <div class="d-flex justify-content-center align-self-center gap-1">
                                                            <img src="http://127.0.0.1:8000/asset/home/delete.png" alt="delete" class="remove">
                                                            <small class="h-sm text-nowrap">Remove form list</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-end">
                                                    <h6 class="h-sms ">Sub Total</h6>
                                                    <h6 class="b-ia h-sms">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-end align-items-center">
                                                    <h6 class="free-del h-sms">Free delivery</h6>
                                                    <del class="del-clr text-secondary lh-lg text-opacity-50">{{ $ip_data->currency_symbol??'₹' }} 299.00</del>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-end align-items-center">
                                                    <h6 class="fw-bold">Total</h6>
                                                    <h6 class="fw-bold price">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                </div>
                                                <div class="text-end pt-2">
                                                    <a href="{{url('/view-invoice')}}"><small class=" free-del text-decoration-underline h-sms">View Invoice</small></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-2 tabs_nd_mbl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="card border-0">
                                                    <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                </div>
                                            </div>
                                            <div class="col-9 ">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 ">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <h6>Order ID : <h6 class="fw-bold"> SKY115</h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown">
                                                        <img class=" dropdown-toggle icons-menu" src="{{asset('asset/home/icons-menu.svg')}}" alt="menu" data-bs-toggle="dropdown">
                                                        <ul class="dropdown-menu p-2" aria-labelledby="navbarDropdownMenuLink">
                                                            <li>Remove from list </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between py-1 align-items-center">
                                                    <small>Total<small class="fw-bold ps-1">{{ $ip_data->currency_symbol??'₹' }} 299.00</small></small>
                                                    <h4 class="fw-bold buy_now"> Buy again</h4>
                                                </div>
                                                <div class="d-flex gap-2 pb-2">
                                                    <h6>status : </h6>
                                                    <h6 class="fw-bold price">Delivered</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-summary-bg py-1">
                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header d-flex justify-content-center gap-2 align-items-center" id="panelsStayOpen-headingOne">
                                                    <div class="text-center view-more" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                        View more
                                                    </div>
                                                        <img src="{{asset('asset/home/viewmore.png')}}" alt="view more" class="rounded-circle">
                                                </h2>
                                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
                                                    <div class="accordion-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6>Sub Total</h6>
                                                            <h6 class="price">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center py-1">
                                                            <h6 class="free-del">Free Delivery</h6>
                                                            <del class="del-clr price">{{ $ip_data->currency_symbol??'₹' }} 299.00</del>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center jkf">
                                                            <h6 class="fw-bold">Total</h6>
                                                            <h6 class="fw-bold price">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                        </div>
                                                        <div class="text-end pt-2">
                                                        <h6 class=" free-del text-decoration-underline ">View Invoice</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-4">
                                <div class="card pt-2 tabs_nd_mbls">
                                    <div class="container-fluid">
                                        <div class="row pb-3 pe-2">
                                            <div class="col-xl-8 col-lg-9 col-md-9 col-sm-9 col-8">
                                                <div class="row">
                                                    <div class="col-4 text-center">
                                                        <div class="card border-0">
                                                            <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                        </div>
                                                    </div>
                                                    <div class="col-8 align-self-center">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1 h-sms fw-normal">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 h-sm ">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <small>Order ID : <small class="fw-bold"> SKY115</small></small>
                                                        </div>
                                                        <div class="py-1">
                                                            <h6 class="h-sms">Last buy : January 10, 2024</h6>
                                                        </div>
                                                        <div class="py-2">
                                                            <div class="d-flex gap-2 align-items-center fsz">
                                                                <h6 class="h-sms">status : </h6>
                                                                <h6 class=" h-sms buy-color">Delivery on progress</h6>&nbsp;|&nbsp;
                                                                <a href="" class="h-sms free-del text-decoration-underline ">Track my order</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-3 col-md-3 col-sm-3 col-4 order-track align-self-center ps-0">
                                                <div class="d-flex justify-content-end py-1">
                                                    <div class="card p-1">
                                                        <div class="d-flex justify-content-center align-self-center gap-2">
                                                            <img src="{{asset('asset/home/unavailable.png')}}" alt="delete" class="remove">
                                                            <small class="h-sm">Cancel order</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-end">
                                                    <h6 class="h-sms">Sub Total</h6>
                                                    <h6 class="b-ia h-sms">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-end align-items-center fsz">
                                                    <h6 class="free-del h-sms">Free delivery</h6>
                                                    <del class="del-clr text-secondary  lh-lg text-opacity-50">{{ $ip_data->currency_symbol??'₹' }} 299.00</del>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-end align-items-center">
                                                    <h6 class="fw-bold">Total</h6>
                                                    <h6 class="fw-bold price">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                </div>
                                                <div class="text-end pt-2">
                                                    <a href=""><small class=" free-del text-decoration-underline">View Invoice</small></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card pt-2 tabs_nd_mbl">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="card border-0">
                                                    <img src="{{asset('asset/home/Group 32126.png')}}" alt="" class="">
                                                </div>
                                            </div>
                                            <div class="col-9 ">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <h6 class="fw-bold">Jaggery powder</h6>
                                                        </div>
                                                        <h6 class="text-secondary opacity-50 py-1">Size : 100 g</h6>
                                                        <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center ">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 18.png" alt="star" class="star1">
                                                            <img src="http://127.0.0.1:8000/asset/home/Polygon 22.png" alt="star" class="star1">
                                                            <h6 class="text-secondary text-opacity-50 ">10 reviews</h6>
                                                        </div>
                                                        <div class="d-flex gap-1 py-1">
                                                            <h6>Order ID : <h6 class="fw-bold"> SKY115</h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown">
                                                        <img class=" dropdown-toggle icons-menu" src="{{asset('asset/home/icons-menu.svg')}}" alt="menu" data-bs-toggle="dropdown">
                                                        <ul class="dropdown-menu p-2" aria-labelledby="navbarDropdownMenuLink">
                                                            <li>Remove from list </li>
                                                            <li>Cancel order</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between py-1 align-items-center">
                                                    <small>Total<small class="fw-bold ps-1">{{ $ip_data->currency_symbol??'₹' }} 299.00</small></small>
                                                    <h4 class="fw-bold buy_now"> Buy again</h4>
                                                </div>
                                                <div class="d-flex gap-2 align-items-center pb-2">
                                                    <h6>status : </h6>
                                                    <h6 class="fw-bold price">Delivery on progress</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-summary-bg py-1">
                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="panelsStayOpen-headingtow">
                                                    <div class="text-center view-more" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsetwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                        View more
                                                    </div>
                                                </h2>
                                                <div id="panelsStayOpen-collapsetwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingtwo">
                                                    <div class="accordion-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6>Sub Total</h6>
                                                            <h6 class="price">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center py-1">
                                                            <h6 class="free-del">Free Delivery</h6>
                                                            <del class="del-clr price">{{ $ip_data->currency_symbol??'₹' }} 299.00</del>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center jkf">
                                                            <h6 class="fw-bold">Total</h6>
                                                            <h6 class="fw-bold price">{{ $ip_data->currency_symbol??'₹' }} 299.00</h6>
                                                        </div>
                                                        <div class="text-end pt-2">
                                                        <h6 class=" free-del text-decoration-underline ">View Invoice</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
