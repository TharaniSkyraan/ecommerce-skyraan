<nav class="navbar navbar-expand-lg  sys-view tab-view ">
    <div class="container">
        <div class=" col-6 d-flex align-items-center">
            <a class="navbar-brand " href="{{ route('ecommerce.home') }}" style="color:#4CAF50!important"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="" ></a>
            <button class="navbar-toggler " type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="w-100 position-relative" role="search" id="search">
                <form autocomplete="off">
                    @csrf
                    <div class="input-group">
                        <input class="form-control border-end-0 rounded-end-0" name="search" id="query" wire:model="query" type="search" placeholder="Search" aria-label="Search" autocomplete="off" required>
                        <button class="search-btn btn p-0 border-start-0 rounded-end-2 srch_icon px-2 bg-white cursor" wire:click.prevent="Search"><i class="bi bi-search"></i></button>  
                    </div>  
                </form>
                <div class="pt-1 position-absolute w-100 list-ui {{($show_result)?'':'d-none'}}">
                    <ul class="list-group text-start rounded-2 px-3" id="result">
                        <h6 class="fw-bold py-3 h-sms"> @if(!empty($query)) Results ({{ count($products)}}) @else Suggestion's @endif</h6>
                        @forelse($products as $product)
                            <div class="p-2">
                                <li  class="container-fluid">
                                    <a href="{{ route('ecommerce.product.detail', ['slug' => $product['slug']]) }}?prdRef={{ \Carbon\Carbon::parse($product['created_at'])->timestamp}}" class="row">
                                        <div class="col-3">
                                            <div class="card card1 position-relative border-0">
                                                <img src="{{$product['image']}}" alt="list_items" class="srch-img">
                                            </div>
                                        </div>
                                        <div class="col-9 price_info">
                                            <h6 class="fw-bold h-sms">{{$product['name']}}</h6>
                                            @if($product['discount']!=0)
                                            <div class="d-flex gap-2 align-items-center">
                                                <del class="text-secondary opacity-75 del-clr h-sms">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</del>
                                                <small class="fw-bold py-1 price">{{ $ip_data->currency_symbol??'₹' }} {{$product['sale_price']}}</small>
                                                <small class="off">{{$product['discount']}}% off</small>
                                            </div>
                                            @else                                    
                                                <small class="fw-bold py-1 price">{{ $ip_data->currency_symbol??'₹' }} {{$product['price']}}</small>
                                            @endif
                                            <div class="d-flex gap-xl-1 gap-lg-1 gap-0 align-items-center">
                                                @if($product['review']==0)
                                                <img src="{{asset('asset/home/0.svg')}}" alt="star" class="sub_star" >
                                                @elseif($product['review']==1)
                                                <img src="{{asset('asset/home/1.svg')}}" alt="star" class="sub_star">
                                                @elseif($product['review']==2)
                                                <img src="{{asset('asset/home/2.svg')}}" alt="star" class="sub_star">
                                                @elseif($product['review']==3)
                                                <img src="{{asset('asset/home/3.svg')}}" alt="star" class="sub_star">
                                                @elseif($product['review']==4)
                                                <img src="{{asset('asset/home/4.svg')}}" alt="star" class="sub_star">
                                                @elseif($product['review']==5)
                                                <img src="{{asset('asset/home/5.svg')}}" alt="star" class="sub_star">
                                                @endif
                                                <h6 class="text-secondary text-opacity-50 h-sms">{{$product['review_count']}} reviews</h6>         
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        @empty
                            <div class="p-2">
                                <li  class="container-fluid">No product found<li>
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    
        <div class="col-6 ps-xl-2 ps-lg-1 ps-0 align-self-center">
            <div class="collapse navbar-collapse " id="navbarScroll">
                <ul class="navbar-nav  my-2 my-lg-0 navbar-nav-scroll menu_icons">
                    <a class="nav-link d-flex align-items-center px-3 gap-1" aria-current="page" href="{{ route('ecommerce.home') }}" ><img src="{{asset('asset/home/home.svg')}}" alt="home" class=""><h6 class="text-dark h-sms">Home</h6></a>
                    <!-- <li class="nav-item dropdown menu-large px-3 py-3 d-flex align-items-center">
                        <a href="javascript:void(0)" class="dropdown-toggle" id="services" data-toggle="dropdown"><h6 class="h-sms ">All Categories</h6><span class="caret"></span></a>
                        <div class="dropdown-menu megamenu py-0" role="menu">
                            <div class="container-fluid">
                                <div class="width_menu">
                                    <div class="row">
                                        @php $i=0; $j=1; @endphp
                                        @foreach($categories as $key => $category)
                                            @if($i==0) <div class="border_dcz col-md-{{$class_name}} asd {{ ($j % 2 == 0) ? 'bg-grey' : 'bg-white' }}"> @endif
                                            @if(count($category->sub_categories)==0) 
                                            <a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category['slug']]) }}" class="fw-light">
                                                <h5 class="py-1 fw-bold">{{ $category->name }}</h5>
                                            </a>
                                            @else
                                                <h5 class="py-1 fw-bold">{{ $category->name }}</h5>
                                            @endif
                                                @php $i++; @endphp
                                            @if($i==$count) @php  $i=0; $j++; @endphp</div>@endif
                                            @foreach($category->sub_categories as $sub_category)
                                                @if($sub_category->status=='active')
                                                    @if($i==0) <div class="border_dcz col-md-{{$class_name}} asd {{ ($j % 2 == 0) ? 'bg-grey' : 'bg-white' }}"> @endif
                                                        <h6 class="p-1 "><a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $sub_category['slug']]) }}" class="fw-light">{{ $sub_category->name }}</a></h6>
                                                        @php $i++; @endphp
                                                    @if($i==$count) @php $i=0; $j++; @endphp </div>@endif
                                                @endif
                                            @endforeach
                                        @endforeach                                   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> -->
                    
                    <a class="nav-link d-flex align-items-center px-3 gap-1" href="{{ route('ecommerce.product.list', ['type' => 'special','slug' => '']) }}"><img src="{{asset('asset/home/skyraa_spl.svg')}}" alt="home"><h6 class="w-100 text-dark h-sms text-nowrap">Skyraa Specials</h6></a>
                    @if(!Auth::check())
                    <div class="nav-link d-flex align-items-center px-3 gap-1" data-bs-toggle="modal" data-bs-target="#signin"><img src="{{asset('asset/home/login.svg')}}" alt="home"  class="login_nav"><h6 class="text-dark h-sms">Login</h6></div>
                    @else
                    <div>
                        <div class="nav-link align-items-center after-login px-3 mt-0"><a href="javascript:void(0);" class="d-flex gap-1 align-items-center"><img src="{{asset('asset/home/login.svg')}}" alt="home"  class="login_nav"><h6 class="text-dark h-sms">{{auth()->user()->name}}</h6><img src="{{asset('asset/home/down-ar.svg')}}" alt="" class="down-ar-nav"></a></div>
                        <div class="box arrow-top position-absolute">
                            <div class=" cursor">
                                <a class="d-flex gap-2 align-items-center" href="{{ route('ecommerce.orders') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="21" viewBox="0 0 20 21" fill="none">
                                    <path d="M19.9511 6.45847L19.2151 10.1168C19.0239 11.0599 18.5133 11.9081 17.7694 12.5184C17.0254 13.1287 16.0937 13.4638 15.1314 13.467H5.61173C5.78364 13.9542 6.10249 14.3761 6.52431 14.6745C6.94612 14.9729 7.45013 15.133 7.96682 15.1329H15.8343C16.0553 15.1329 16.2673 15.2207 16.4236 15.377C16.5799 15.5333 16.6678 15.7453 16.6678 15.9663C16.6678 16.1874 16.5799 16.3994 16.4236 16.5556C16.2673 16.7119 16.0553 16.7997 15.8343 16.7997H7.96682C6.94643 16.7978 5.96204 16.4225 5.19939 15.7446C4.43674 15.0668 3.94858 14.1333 3.82702 13.1202L2.67707 3.36952C2.65261 3.16698 2.55493 2.98036 2.40243 2.84482C2.24994 2.70928 2.05314 2.63416 1.84911 2.63362H0.83348C0.612427 2.63362 0.400428 2.54581 0.244121 2.38952C0.0878128 2.23322 0 2.02124 0 1.80021C0 1.57917 0.0878128 1.36719 0.244121 1.2109C0.400428 1.0546 0.612427 0.966797 0.83348 0.966797H1.85003C2.46241 0.967446 3.05332 1.19245 3.51102 1.59926C3.96872 2.00607 4.26147 2.56648 4.33391 3.17451L4.36703 3.46611H7.5004C7.72145 3.46611 7.93345 3.55391 8.08976 3.71021C8.24607 3.8665 8.33388 4.07848 8.33388 4.29952C8.33388 4.52055 8.24607 4.73253 8.08976 4.88883C7.93345 5.04512 7.72145 5.13293 7.5004 5.13293H4.56666L5.35046 11.7993H15.1342C15.7114 11.7979 16.2703 11.5973 16.7167 11.2314C17.163 10.8655 17.4694 10.3568 17.584 9.79119L18.32 6.13283C18.3433 6.01189 18.3398 5.88731 18.3096 5.76788C18.2795 5.64846 18.2235 5.53712 18.1455 5.44173C18.0676 5.34635 17.9697 5.26925 17.8587 5.21588C17.7476 5.16252 17.6263 5.1342 17.5031 5.13293H14.1673C13.9463 5.13293 13.7343 5.04512 13.578 4.88883C13.4217 4.73253 13.3338 4.52055 13.3338 4.29952C13.3338 4.07848 13.4217 3.8665 13.578 3.71021C13.7343 3.55391 13.9463 3.46611 14.1673 3.46611H17.5012C17.8712 3.46636 18.2365 3.54869 18.5708 3.70717C18.9051 3.86565 19.2 4.09633 19.4344 4.38258C19.6687 4.66882 19.8366 5.0035 19.926 5.36248C20.0153 5.72145 20.0239 6.09578 19.9511 6.45847ZM5.83344 17.6332C5.50375 17.6332 5.18146 17.7309 4.90733 17.9141C4.6332 18.0972 4.41954 18.3575 4.29337 18.6621C4.1672 18.9667 4.13419 19.3018 4.19851 19.6252C4.26283 19.9485 4.42159 20.2455 4.65472 20.4786C4.88785 20.7117 5.18487 20.8705 5.50823 20.9348C5.83159 20.9991 6.16676 20.9661 6.47136 20.8399C6.77596 20.7138 7.0363 20.5001 7.21947 20.226C7.40263 19.9519 7.5004 19.6296 7.5004 19.3C7.49919 18.8583 7.32317 18.435 7.01082 18.1227C6.69846 17.8104 6.27517 17.6344 5.83344 17.6332ZM14.1673 17.6332C13.8376 17.6332 13.5153 17.7309 13.2412 17.9141C12.9671 18.0972 12.7534 18.3575 12.6272 18.6621C12.5011 18.9667 12.4681 19.3018 12.5324 19.6252C12.5967 19.9485 12.7555 20.2455 12.9886 20.4786C13.2217 20.7117 13.5188 20.8705 13.8421 20.9348C14.1655 20.9991 14.5006 20.9661 14.8052 20.8399C15.1098 20.7138 15.3702 20.5001 15.5533 20.226C15.7365 19.9519 15.8343 19.6296 15.8343 19.3C15.8331 18.8583 15.6571 18.435 15.3447 18.1227C15.0323 17.8104 14.6091 17.6344 14.1673 17.6332ZM7.72763 7.00856C7.65281 7.0882 7.5945 7.18187 7.55607 7.28415C7.51763 7.38644 7.49982 7.49532 7.50367 7.60452C7.50752 7.71372 7.53294 7.82108 7.57849 7.92041C7.62403 8.01973 7.68879 8.10906 7.76903 8.18324L9.06709 9.40024C9.29977 9.63407 9.57647 9.81949 9.88122 9.9458C10.186 10.0721 10.5127 10.1368 10.8426 10.1361C11.1658 10.1377 11.4862 10.0751 11.7851 9.95214C12.084 9.82914 12.3555 9.64813 12.5841 9.41956L13.9088 8.186C14.0702 8.03572 14.1653 7.82748 14.1731 7.6071C14.181 7.38672 14.101 7.17224 13.9507 7.01086C13.8004 6.84947 13.5921 6.7544 13.3717 6.74655C13.1513 6.7387 12.9368 6.81872 12.7754 6.969L11.6715 7.99375V1.80021C11.6715 1.57917 11.5837 1.36719 11.4274 1.2109C11.2711 1.0546 11.0591 0.966797 10.838 0.966797C10.6169 0.966797 10.4049 1.0546 10.2486 1.2109C10.0923 1.36719 10.0045 1.57917 10.0045 1.80021V7.99191L8.90057 6.96716C8.82092 6.89235 8.72725 6.83405 8.62496 6.79562C8.52266 6.75718 8.41377 6.73937 8.30456 6.74322C8.19535 6.74707 8.08798 6.7725 7.98865 6.81804C7.88931 6.86358 7.79998 6.92833 7.72579 7.00856H7.72763Z" fill="black"/>
                                </svg>      
                                <span class="h-sms">Orders</span></a>
                            </div>
                            <hr>
                            <div class=" cursor">
                                <a  class="d-flex gap-2 align-items-center" href="{{ route('ecommerce.address-list') }}">
                                <svg width="17" height="21" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.575 13.0023C16.575 13.2373 16.5054 13.467 16.3749 13.6623C16.2444 13.8577 16.0589 14.0099 15.8419 14.0998C15.6248 14.1897 15.386 14.2133 15.1556 14.1674C14.9253 14.1216 14.7136 14.0085 14.5475 13.8423C14.3814 13.6762 14.2683 13.4645 14.2225 13.2341C14.1767 13.0037 14.2002 12.7648 14.2901 12.5478C14.38 12.3307 14.5322 12.1452 14.7275 12.0146C14.9228 11.8841 15.1524 11.8144 15.3874 11.8144C15.7023 11.8144 16.0044 11.9396 16.2272 12.1624C16.4499 12.3851 16.575 12.6873 16.575 13.0023ZM18.9504 6.67266C18.7404 6.67266 18.539 6.75609 18.3905 6.90461C18.242 7.05313 18.1586 7.25456 18.1586 7.4646V16.966C18.1584 17.1759 18.0749 17.3772 17.9264 17.5257C17.778 17.6741 17.5767 17.7576 17.3668 17.7579H4.70016C4.07041 17.7571 3.46667 17.5066 3.02137 17.0612C2.57607 16.6158 2.32556 16.0119 2.32479 15.3821V7.4529C3.00941 7.96796 3.84251 8.24719 4.69918 8.24874H12.6151C12.8251 8.24874 13.0265 8.1653 13.175 8.01678C13.3235 7.86827 13.4069 7.66683 13.4069 7.4568C13.4069 7.24676 13.3235 7.04533 13.175 6.89681C13.0265 6.74829 12.8251 6.66485 12.6151 6.66485H4.70016C4.3683 6.6624 4.04064 6.5904 3.73832 6.45349C3.43601 6.31658 3.16574 6.1178 2.94496 5.86998C3.16572 5.62256 3.43611 5.42443 3.73853 5.28848C4.04095 5.15254 4.3686 5.08183 4.70016 5.08097H10.2417C10.4517 5.08097 10.6531 4.99753 10.8016 4.84901C10.9501 4.7005 11.0335 4.49906 11.0335 4.28903C11.0335 4.07899 10.9501 3.87756 10.8016 3.72904C10.6531 3.58052 10.4517 3.49708 10.2417 3.49708H4.70016C3.65047 3.4994 2.64451 3.91777 1.90264 4.66051C1.16076 5.40326 0.743475 6.40984 0.742188 7.45972L0.742188 15.3772C0.743478 16.4267 1.16089 17.4329 1.90287 18.175C2.64486 18.9172 3.65083 19.3346 4.70016 19.3359H17.3668C17.9966 19.3352 18.6003 19.0846 19.0456 18.6392C19.4909 18.1938 19.7414 17.59 19.7422 16.9601V7.45972C19.7409 7.25053 19.6569 7.05035 19.5086 6.90288C19.3602 6.75542 19.1596 6.67265 18.9504 6.67266ZM13.9647 3.66776L14.9915 2.65345V7.45972C14.9915 7.66976 15.0749 7.87119 15.2234 8.01971C15.3719 8.16823 15.5732 8.25166 15.7832 8.25166C15.9932 8.25166 16.1946 8.16823 16.3431 8.01971C16.4916 7.87119 16.575 7.66976 16.575 7.45972V2.66808L17.6067 3.67166C17.7571 3.81807 17.9595 3.89872 18.1693 3.89589C18.3791 3.89305 18.5793 3.80696 18.7256 3.65654C18.872 3.50613 18.9527 3.30372 18.9498 3.09384C18.947 2.88395 18.8609 2.68379 18.7105 2.53739L16.9475 0.820862C16.6351 0.510269 16.2125 0.335938 15.772 0.335938C15.3315 0.335938 14.9089 0.510269 14.5965 0.820862L12.8511 2.54226C12.7771 2.6154 12.7183 2.70238 12.678 2.79825C12.6376 2.89412 12.6166 2.997 12.616 3.10102C12.6148 3.31108 12.6971 3.51302 12.8448 3.6624C12.9924 3.81177 13.1934 3.89636 13.4034 3.89755C13.6134 3.89874 13.8153 3.81643 13.9647 3.66874V3.66776Z" fill="black"/>
                                </svg>                           
                                <span class="h-sms">Address</span></a>
                            </div>
                            <hr>
                            <div class=" cursor">
                                <a class=" d-flex gap-2 align-items-center" href="{{ route('ecommerce.account') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="21" viewBox="0 0 20 21" fill="none">
                                    <path d="M7.2777 10.8774C8.25163 10.878 9.20385 10.5852 10.0139 10.0361C10.824 9.48698 11.4555 8.70624 11.8286 7.79262C12.2016 6.879 12.2995 5.87353 12.1099 4.9034C11.9202 3.93326 11.4515 3.04205 10.763 2.34247C10.0745 1.6429 9.19719 1.1664 8.24201 0.973237C7.28683 0.780077 6.2967 0.878935 5.39685 1.25731C4.497 1.63568 3.72787 2.27657 3.18672 3.09892C2.64558 3.92126 2.35674 4.88811 2.35674 5.87719C2.35891 7.20217 2.87798 8.4723 3.80029 9.40947C4.7226 10.3466 5.97299 10.8745 7.2777 10.8774ZM7.2777 2.5437C7.92691 2.5437 8.56154 2.7392 9.10134 3.10549C9.64114 3.47178 10.0619 3.9924 10.3103 4.60152C10.5587 5.21063 10.6237 5.88088 10.4971 6.52752C10.3704 7.17415 10.0578 7.76812 9.59875 8.23432C9.13969 8.70051 8.55481 9.018 7.91808 9.14662C7.28134 9.27525 6.62135 9.20923 6.02155 8.95693C5.42176 8.70462 4.90911 8.27736 4.54843 7.72917C4.18774 7.18098 3.99523 6.53649 3.99523 5.87719C3.9974 4.99402 4.34402 4.14771 4.95921 3.52348C5.5744 2.89925 6.40805 2.54795 7.2777 2.54648V2.5437ZM8.09147 13.3261C8.1173 13.545 8.05678 13.7654 7.92314 13.9392C7.7895 14.113 7.59358 14.226 7.37816 14.2536C5.99126 14.4339 4.71645 15.1209 3.792 16.1862C2.86754 17.2515 2.35669 18.6222 2.35491 20.0422C2.35491 20.2636 2.26831 20.4759 2.11416 20.6325C1.96 20.789 1.75093 20.877 1.53292 20.877C1.31492 20.877 1.10584 20.789 0.951692 20.6325C0.797539 20.4759 0.710938 20.2636 0.710938 20.0422C0.712238 18.2154 1.36937 16.4518 2.55921 15.0818C3.74905 13.7118 5.38995 12.8293 7.17449 12.5998C7.39007 12.5736 7.60712 12.635 7.77824 12.7708C7.94935 12.9065 8.06065 13.1054 8.08781 13.3242L8.09147 13.3261ZM19.3372 16.8219L18.538 16.3581C18.8422 15.512 18.8422 14.584 18.538 13.7379L19.3372 13.2741C19.5137 13.1575 19.6394 12.9766 19.6884 12.7686C19.7374 12.5606 19.7059 12.3415 19.6005 12.1564C19.4951 11.9713 19.3237 11.8344 19.1218 11.7738C18.9198 11.7132 18.7027 11.7337 18.5152 11.8309L17.7169 12.2947C17.1338 11.6172 16.3493 11.151 15.482 10.9665V10.0445C15.482 9.82312 15.3954 9.61079 15.2413 9.45424C15.0871 9.2977 14.8781 9.20975 14.6601 9.20975C14.442 9.20975 14.233 9.2977 14.0788 9.45424C13.9247 9.61079 13.8381 9.82312 13.8381 10.0445V10.9618C12.9711 11.147 12.1868 11.6132 11.6032 12.29L10.8049 11.8263C10.6174 11.729 10.4003 11.7086 10.1983 11.7692C9.9964 11.8297 9.82505 11.9667 9.71962 12.1518C9.6142 12.3369 9.58274 12.556 9.63173 12.764C9.68073 12.9719 9.80644 13.1529 9.98295 13.2695L10.7821 13.7332C10.4779 14.5793 10.4779 15.5074 10.7821 16.3535L9.98295 16.8172C9.88358 16.8687 9.79559 16.9403 9.72432 17.0274C9.65305 17.1146 9.6 17.2156 9.5684 17.3243C9.5368 17.4329 9.52731 17.547 9.54051 17.6595C9.55371 17.772 9.58933 17.8806 9.64519 17.9786C9.70105 18.0767 9.77598 18.1622 9.86543 18.2299C9.95487 18.2977 10.057 18.3462 10.1655 18.3725C10.274 18.3989 10.3866 18.4026 10.4966 18.3833C10.6065 18.364 10.7114 18.3222 10.8049 18.2604L11.6032 17.7967C12.1864 18.4741 12.9708 18.9404 13.8381 19.1249V20.0422C13.8381 20.2636 13.9247 20.4759 14.0788 20.6325C14.233 20.789 14.442 20.877 14.6601 20.877C14.8781 20.877 15.0871 20.789 15.2413 20.6325C15.3954 20.4759 15.482 20.2636 15.482 20.0422V19.1249C16.349 18.9397 17.1333 18.4735 17.7169 17.7967L18.5152 18.2604C18.7027 18.3577 18.9198 18.3781 19.1218 18.3175C19.3237 18.257 19.4951 18.12 19.6005 17.9349C19.7059 17.7498 19.7374 17.5307 19.6884 17.3227C19.6394 17.1148 19.5137 16.9338 19.3372 16.8172V16.8219ZM14.6628 17.5435C14.1751 17.5435 13.6983 17.3966 13.2928 17.1214C12.8873 16.8462 12.5712 16.4551 12.3845 15.9975C12.1979 15.5399 12.1491 15.0364 12.2442 14.5506C12.3394 14.0648 12.5742 13.6186 12.9191 13.2684C13.264 12.9181 13.7034 12.6796 14.1817 12.583C14.6601 12.4864 15.1559 12.536 15.6065 12.7255C16.0571 12.9151 16.4422 13.236 16.7132 13.6479C16.9841 14.0597 17.1288 14.5439 17.1288 15.0392C17.1289 15.368 17.0652 15.6937 16.9414 15.9976C16.8176 16.3015 16.636 16.5776 16.4071 16.8102C16.1782 17.0429 15.9065 17.2274 15.6073 17.3534C15.3082 17.4794 14.9875 17.5443 14.6637 17.5444L14.6628 17.5435Z" fill="black"/>
                                </svg>
                                <span class="h-sms">Account Settings</span></a>
                            </div>
                            <hr>
                            <div class="cursor">
                                <a class="d-flex gap-2 align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="21" viewBox="0 0 19 22" fill="none">
                                    <path d="M10.6434 3.28125V4.80863C12.4469 5.12384 14.0814 6.06506 15.2594 7.46666C16.4373 8.86826 17.0832 10.6404 17.0833 12.4712C17.0834 13.4926 16.8823 14.5041 16.4916 15.4477C16.1008 16.3914 15.5278 17.2489 14.8056 17.9712C14.0834 18.6935 13.2261 19.2664 12.2825 19.6573C11.3389 20.0482 10.3275 20.2494 9.30612 20.2494C8.28468 20.2494 7.27319 20.0482 6.3295 19.6573C5.38581 19.2664 4.52821 18.6935 3.80594 17.9712C3.08368 17.249 2.51089 16.3915 2.12 15.4478C1.72911 14.5041 1.5278 13.4927 1.5278 12.4712C1.52806 10.6404 2.17406 8.8684 3.35195 7.46684C4.52984 6.06527 6.16425 5.12402 7.96767 4.80863V3.28125C5.75942 3.60299 3.74085 4.70892 2.28104 6.39678C0.821238 8.08463 0.0176533 10.2416 0.0175781 12.4732C0.0178313 14.9364 0.996663 17.2987 2.73854 19.0404C4.48041 20.7821 6.84287 21.7605 9.30612 21.7605C11.7692 21.7603 14.1313 20.7817 15.873 19.0401C17.6147 17.2984 18.5933 14.9363 18.5935 12.4732C18.5932 10.2417 17.7896 8.08479 16.3298 6.39698C14.87 4.70917 12.8516 3.60318 10.6434 3.28125Z" fill="black"/>
                                    <path d="M8.55127 1.0768L8.55127 15.0675C8.55127 15.483 8.88805 15.8198 9.3035 15.8198C9.71895 15.8198 10.0557 15.483 10.0557 15.0675V1.0768C10.0557 0.661357 9.71895 0.324572 9.3035 0.324572C8.88805 0.324572 8.55127 0.661357 8.55127 1.0768Z" fill="black"/>
                                </svg>
                                <span class="h-sms">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(Auth::check())
                        <a href="{{route('ecommerce.wish-lists')}}" class="d-flex align-items-center px-3 nav-cart gap-2" type="button">
                            <img src="{{asset('asset/home/btm-like.svg')}}" alt="home" class="like_nav">
                        </a>
                    @endif
                    <div wire:ignore class="pt-0">
                        @if((Route::currentRouteName() != 'ecommerce.cart') && (Route::currentRouteName() != 'ecommerce.checkout'))
                            <div class="btn d-flex align-items-center px-3 nav-cart gap-2 cartGo" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <span class="badge cartCount p-1">{{ $cart_quantity }}</span>
                                <img src="{{asset('asset/home/cart.svg')}}" alt="home" class="cart_img_nav">
                            </div>
                        @else
                            <div class="btn d-flex align-items-center px-3 nav-cart gap-2">
                                <span class="badge cartCount">{{ $cart_quantity }}</span>
                                <img src="{{asset('asset/home/cart.svg')}}" alt="home" class="cart_img_nav">
                            </div>
                        @endif
                    </div>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="tab-views mbl-view search_menu_nav mobile-navbar" id="top-menu-res">
    <div class="ps-md-2 ps-sm-2 ps-0 pe-md-3 pe-sm-3 pe-3 py-2 d-flex justify-content-between align-items-center">
        <div class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuopen" aria-controls="offcanvasExample" id="menuIcon">
            <img src="{{asset('asset/home/responsive-menu.svg')}}" alt="menu" >
        </div>
        <a href="{{ url('/') }}"><img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="" class="responsive_logo"></a>
        <div class="top_re_cart" wire:ignore>
        @if((Route::currentRouteName() != 'ecommerce.cart') && (Route::currentRouteName() != 'ecommerce.checkout'))
            <div class="btn cart-btn cartGo" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" >
                <img src="{{asset('asset/home/cart.svg')}}" alt="cart">
                <span class="text-white cartCount h-sm">{{ $cart_quantity }}</span>
            </div>
        @else
            <div class="btn cart-btn">
                <img src="{{asset('asset/home/cart.svg')}}" alt="cart">
                <span class="text-white cartCount h-sm">{{ $cart_quantity }}</span>
            </div>
        @endif
        </div>
    </div>
    
    <!-- Menu offcanva right -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="menuopen" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header pt-3">
            <h5 class="fw-bold menu-font" id="offcanvasExampleLabel">Menu</h5>
            <img id="closeIcon" src="{{asset('asset/home/close-vector.svg')}}" alt="close_icon" class="cursor">
        </div>
        <hr>
        <div class="offcanvas-body">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed  h-sm" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            All categories
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="h-sms">
                            <ul>
                                @foreach($categories as $category)
                                    <li class="main-item">
                                        <div class="d-flex justify-content-between align-items-center py-1">
                                            <span class="detail-dot"><a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category->slug]) }}" class="fw-bold-res">{{ $category->name }}</a></span>
                                            @if(count($category->sub_categories) > 0)
                                                <span class="toggle-symbol sybl fw-bold-res ">+</span>
                                            @endif
                                        </div>
                                        @if(count($category->sub_categories) > 0)
                                            <ul class="sub-list ps-3 fw-bold">
                                                @foreach($category->sub_categories as $sub_category)
                                                    @if($sub_category->status=='active')
                                                        <li><a href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $sub_category['slug']]) }}" >{{ $sub_category->name }}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @if(\Auth::check())
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed  h-sm" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseOne">
                         My Account                        
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="h-sms">
                            <ul>
                                <li class="pb-2">
                                    <a href="{{ route('ecommerce.orders') }}">
                                        <div class="d-flex gap-xl-3 gap-lg-3 gap-md-3 gap-sm-2 gap-1 align-items-center text-nowrap justify-content-start">
                                            <img src="{{asset('asset/home/dashboard-5.svg')}}" alt="order-histroy">
                                            Orders
                                        </div>
                                    </a>
                                </li>
                                <li class="pb-2">
                                    <a href="{{ route('ecommerce.address-list') }}">
                                        <div class="d-flex gap-xl-3 gap-lg-3 gap-md-3 gap-sm-2 gap-1 align-items-center text-nowrap justify-content-start">
                                            <img src="{{asset('asset/home/dashboard-2.svg')}}" alt="order-histroy">
                                            Address
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('ecommerce.account') }}">
                                        <div class="d-flex gap-xl-3 gap-lg-3 gap-md-3 gap-sm-2 gap-1 align-items-center text-nowrap justify-content-start">
                                            <img src="{{asset('asset/home/dashboard-4.svg')}}" alt="order-histroy">
                                            Account Settings
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div  class="menu-list">
                <a href="{{ route('ecommerce.product.list', ['type' => 'special','slug' => '']) }}"><h5 class="li-item fw-normal">Skyraa Specials</h5></a>
                <button class="btn px-1 pt-0 pb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">Search</button>
                <a href="{{url('/aboutus')}}"><h5 class="li-item fw-normal">About us</h5></a>
                <a href="{{url('/contactus')}}"><h5 class="li-item fw-normal">Contact us</h5></a>
            </div>
        </div>
        <hr>
        @if(!Auth::check())
            <div class="px-5 text-center login pb-5 pt-3" >
                <div class="card border-0  py-2 px-4">
                    <div class="d-flex gap-2 justify-content-center align-items-center" data-bs-toggle="modal" data-bs-target="#signin">
                        <img src="{{asset('asset/home/login.svg')}}" alt="login" class="login-svg">
                        <h5 class="text-white text-center  h-sm">Login</h5>
                    </div>
                </div>
            </div>
        @else
            <div class="px-5 text-center login pb-5 pt-3" >
                <div class="card border-0  py-2 px-4">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <div class="d-flex gap-2 justify-content-center align-items-center">
                            <img src="{{asset('asset/home/logout.svg')}}" alt="logout" class="logout" >
                            <div>
                                <h5 class="text-white text-center h-sm">Logout</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>        
            <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endif
    </div>
</div>

<!-- Edit popup -->    
<div class="modal fade" id="Editpopup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="text-end">
            <img src="{{asset('asset/home/close.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close" class="close-btn">
        </div>
        <div class="modal-content rounded-0 border-0">
            <div class="modal-bodys p-4">
                @livewire('ecommerce.layout.quick-shop')
            </div>
        </div>
    </div>
</div>

<!-- notifyme -->    
<div class="modal fade" id="notifyMepopup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content border-0">
            @livewire('ecommerce.layout.notify-me')           
        </div>
    </div>
</div>
@push('scripts')
<script src="{{asset('asset/livewire/js/nav.js')}}"></script>
@endpush