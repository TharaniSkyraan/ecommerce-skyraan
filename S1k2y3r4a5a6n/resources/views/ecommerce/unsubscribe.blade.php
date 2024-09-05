<x-ecommerce.app-layout>   
    <style>
        .card{
            width:100%;
            padding:8% 10%;
            display: block;
        }        
        .card .form-check-input:checked {
            background-color: var(--next-prev-arrow-color) !important;
        }
        .card .form-check-input {
            border: var(--bs-border-width) solid var(--next-prev-arrow-color) !important;
        }
        .form-check-input:checked[type=radio] {
            --bs-form-check-bg-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e);
        }
        .card .form-check-input:focus {
            box-shadow: 0 0 10px var(--items-list-border-color) !important;
        }
        .card .btn {
            background-color: var(--next-prev-arrow-color);
        }
    </style>
    @livewire('ecommerce.unsubscribe', ['email' => $email])
</x-ecommerce.app-layout>