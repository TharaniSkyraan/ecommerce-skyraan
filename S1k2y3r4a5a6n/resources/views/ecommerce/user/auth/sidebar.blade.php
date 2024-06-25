<div class="card card1 border-0 p-3 d-flex d-sm-flex flex-sm-row d-md-block flex-row align-self-center inside-cd">
    <div class="pb-xl-3 pb-lg-3 pb-md-2 pb-sm-0 pb-0">
        <div class="card py-2 border-0 px-xl-4 px-lg-4 px-sm-4 px-md-4 px-2 cursor-pointer adasd {{ (Route::currentRouteName() == 'ecommerce.orders') ? 'active' : '' }}">
            <a href="{{ route('ecommerce.orders') }}">
                <div class="d-flex gap-xl-3 gap-lg-3 gap-md-2 gap-sm-1 gap-1 align-items-center text-nowrap justify-content-start">
                    <img src="{{asset('asset/home/dashboard-5.svg')}}" alt="order-histroy">
                    <h6 class="text-dark">My Orders</h6>
                </div>
            </a>
        </div>
    </div>
    <div class="pb-xl-3 pb-lg-3 pb-md-2 pb-sm-0 pb-0">
        <div class="card py-2 border-0 px-xl-4 px-lg-4 px-sm-4 px-md-4 px-2 cursor-pointer {{ (Route::currentRouteName() == 'ecommerce.address-list') ? 'active' : '' }}">
            <a href="{{ route('ecommerce.address-list') }}">
                <div class="d-flex gap-xl-3 gap-lg-3 gap-md-2 gap-sm-1 gap-1 align-items-center text-nowrap justify-content-start">
                    <img src="{{asset('asset/home/dashboard-2.svg')}}" alt="delivery">
                    <h6 class="text-dark">Address</h6>
                </div>
            </a>
        </div>
    </div>
    <div class="pb-xl-3 pb-lg-3 pb-md-2 pb-sm-0 pb-0">
        <div class="card py-2 border-0 px-xl-4 px-lg-4 px-sm-4 px-md-4 px-2 cursor-pointer {{ (Route::currentRouteName() == 'ecommerce.account') ? 'active' : '' }}">
            <a href="{{ route('ecommerce.account') }}">
                <div class="d-flex gap-xl-3 gap-lg-3 gap-md-2 gap-sm-1 gap-1 align-items-center text-nowrap justify-content-start">
                    <img src="{{asset('asset/home/dashboard-4.svg')}}" alt="account-setting">
                    <h6 class="text-dark">Account Settings</h6>
                </div>
            </a>
        </div>
    </div>
    <div class="pb-xl-3 pb-lg-3 pb-md-2 pb-sm-0 pb-0">
        <div class="card py-2 border-0 px-xl-4 px-lg-4 px-sm-4 px-md-4 px-2 cursor-pointer">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                <div class="d-flex gap-xl-3 gap-lg-3 gap-md-2 gap-sm-1 gap-1 align-items-center text-nowrap justify-content-start">
                    <img src="{{asset('asset/home/dashboard-3.svg')}}" alt="logout-auth">
                    <h6 class="text-dark">Log out</h6>
                </div>
            </a>
        </div>
    </div>
</div>