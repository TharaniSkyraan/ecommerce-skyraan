<!doctype html>
<html lang="en">
    <head>
        @include('common.meta')
        <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
            
        <!-- Styles -->
        @livewireStyles
        
        <style>
            :root {
                --next-prev-arrow-color: {{ $siteSetting->theme_primary_color }};
                --side-content-color: #822201;
                --price-content-color:{{ $siteSetting->theme_secondary_color }};
                --alterprice-content-color: #C4C4C4;
                --items-list-bg-color:{{ $siteSetting->theme_tertiary_color }};
                --items-list-border-color:{{ $siteSetting->theme_tertiary_color }};
                --items-red-color:#FF0000;
                --price-tag-color:#FF5252;
                --password-red-color:#DB4437;
                --password-green-color:#0F9D58;
                --aboutus-color:#f9f9f9c9;
            }
            </style>
        @include('common.top_css')
        
        @if(isset($customstyle))
            {{$customstyle}} <!-- Yield Customstyle -->
        @endif
        <script src="{{asset('asset/jquery/jquery.min.js')}}"></script>
    </head>
    <body>
        @include('common.navbar')
        
            {{ $slot }} <!-- Yield Content -->
         
        @include('common.footer')

        @include('common.bottom_script')
        
        @if(isset($customscript))
            {{$customscript}} <!-- Yield Customscript -->
        @endif
        
        @stack('scripts')
        @livewireScripts
        
    </body>
</html>
