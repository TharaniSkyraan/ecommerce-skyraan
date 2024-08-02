<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
        <title>Side Navigation Bar in HTML CSS JavaScript</title>
        <link rel="stylesheet" href="{{ asset('admin/style.css')}}" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Styles -->
        @livewireStyles
        @if (isset($styles))
            {{$styles}}
        @endif

    </head>
    <body class="font-sans antialiased "   >
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('header')
            @livewire('sidebar')
            

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <div class="main-container">
                {{ $slot }}
            </div>
        </div>

        @stack('modals')

        @if(isset($scripts))
            {{$scripts}}
        @endif
    <script src="{{ asset('admin/script.js')}}"></script>
        @livewireScripts
        @stack('scripts')

    </body>
</html>
