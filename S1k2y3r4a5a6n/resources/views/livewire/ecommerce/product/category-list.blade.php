<section  class="product">
<div class="container-fluid px-xl-5 px-lg-5 px-sm-5 px-md-5 px-3 position-relative category_list_nav">
    <div id="product" class="owl-carousel px-3">
        @foreach($categories as $category)
            <div class="d-flex item text-center h-sms dropdown position-relative align-items-center gap-2 justify-content-center" id="dropdown">
                <a class="">{{$category->name}}</a>
                <img src="{{asset('asset/home/down-ar.svg')}}" alt="arrow">
            </div>
        @endforeach
    </div>
    <div class="dropdown-menu megamenu py-0" role="menu">
        <div class="container-fluid">
            <div class="width_menu">
                <div class="row">
                    <p>jewfjnjf</p>  
                    <p>jewfjnjf</p>                              
                    <p>jewfjnjf</p>                              
                    <p>jewfjnjf</p>                              
                    <p>jewfjnjf</p>                              
                    <p>jewfjnjf</p>                              
                </div>
            </div>
        </div>
    </div>
</div>

</section>

@push('scripts')
<script>
$(document).ready(function(){
    $('#product').owlCarousel({
        // loop:true,
        margin:10,
        nav:true,
        dots:false,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:4 // Instead of 4
            },
            1000:{
                items:10 // Instead of 10
            }
        }
    });
});
</script>
@endpush