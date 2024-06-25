<x-ecommerce.app-layout>   
    <x-slot name="customstyle">
        <link href="{{asset('asset/css/dashboard.css')}}" rel="stylesheet" type="text/css" />
    </x-slot>
    <section class="bread-crumb">
        <div class=" px-xl-5 px-lg-5 px-md-5 px-sm-3 px-3 py-2">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23000000'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ecommerce.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Account</a></li>
                </ol>
            </nav>
        </div>
    </section>
    @livewire('ecommerce.user.auth.account-setting')

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
    <x-slot name="customscript">
        <script src="{{asset('asset/js/dashboard.js')}}"></script>
    </x-slot>
</x-ecommerce.app-layout>