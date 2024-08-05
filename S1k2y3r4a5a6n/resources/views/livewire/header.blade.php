
<nav class="navbar">
    <div class="logo_item">
        <i class="bx bx-menu" id="sidebarOpen"></i>
        <img src="{{asset('storage/'.$siteSetting->site_logo)}}" alt="">
        {{$siteSetting->site_name}}
    </div>

    <!-- <div class="search_bar">
    <input type="text" placeholder="Search" />
    </div> -->

    <div class="navbar_content">
    <i class="bi bi-grid"></i>
    <i class='bx bx-sun d-none' id="darkLight"></i>

    {{ \Auth::guard('admin')->user()->name }}
    @if(\Auth::guard('admin')->user()->profile_photo_path)
        <img src="{{ asset('storage') }}/{{ \Auth::guard('admin')->user()->profile_photo_path }}" alt="Brand-icon" class="profile">
    @else
        <img src="{{ asset('admin/images/profile.jpg') }}" alt="Brand-icon" class="profile">
    @endif
    <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
        <i class='bx bx-log-out-circle'></i>
    </a>
        
    <form id="logout-form-header" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    </div>
</nav>
<div class="overlay"></div>