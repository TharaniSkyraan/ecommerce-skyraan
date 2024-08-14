<x-ecommerce.app-layout>  
    <style>
        .top-nav{
            display:none;
        }
        .search_menu_nav{
            display:none;
        }
        #footer{
            display:none;
        }
    </style> 
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/header.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    <section class="page-not-found pt-5">
        <div class="container pt-5">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-7 col-sm-10 col-md-10 col-12 text-center py-5">
                    <h1 class="py-2 four-zero">500</h1>
                    <h4 class="fw-bold py-2">SERVER ERROR</h4>
                    <small class="py-2 lh-base text-secondary">There seems to be an issue with our server. We're working to fix it as soon as possible.</small>
                    <div class="py-3 w-100 d-flex justify-content-center py-4">
                        <a href="{{url('/')}}" class="border btn rounded-2 glow px-4 py-2 d-flex gap-2 align-items-center justify-content-center">
                            <span class="">HOME</span>
                            <img src="{{asset('asset/home/arrow_1.svg')}}" alt="image">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-ecommerce.app-layout>