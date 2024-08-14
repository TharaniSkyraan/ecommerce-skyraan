<x-ecommerce.app-layout>  
    <style>
        .about-us-banner {
            background-image: url('{{ asset('asset/home/abt-header.svg') }}');
            background-size: cover;
            height:150px;
        }
    </style> 
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/header.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    <section class="page-not-found pb-5">
        <div class="container pb-5">
            <div class="row justify-content-center pb-5">
                <div class="col-xl-7 col-lg-7 col-sm-10 col-md-10 col-12 text-center py-5">
                    <h1 class="py-2 four-zero">404</h1>
                    <h4 class="fw-bold py-2">PAGE NOT FOUND</h4>
                    <small class="py-2 lh-base text-secondary">Sorry, but we can’t seem to find the page you’re looking for. It might have been moved, deleted, or you might have mistyped the URL.</small>
                    <div class="py-3 w-100 d-flex justify-content-center py-3">
                        <a href="{{url('/')}}" class="border btn rounded-2 glow px-4 py-2 d-flex gap-2 align-items-center justify-content-center">
                            <span class="">HOME</span>
                                <img src="{{asset('asset/home/arrow_1.svg')}}" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-ecommerce.app-layout>