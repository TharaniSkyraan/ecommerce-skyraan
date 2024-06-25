<section  class="product">
    <div class="container-fluid px-xl-5 px-lg-5 px-sm-5 px-md-5 px-3 py-2">
        <div id="product" class="owl-carousel">
            @foreach($categories as $category)
                <div class="item text-center h-sms "><a class="" href="{{ route('ecommerce.product.list', ['type' => 'category','slug' => $category->slug]) }}">{{$category->name}}</a></div>
            @endforeach
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