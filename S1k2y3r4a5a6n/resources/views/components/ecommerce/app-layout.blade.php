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
    <body class="overlayScroll">
        <div id="overlayer">
            <div class="loader-overlay">
            <svg width="150px" height="75px" viewBox="0 0 187.3 93.7" preserveAspectRatio="xMidYMid meet">
                <path  stroke="#565454" id="outline" fill="none" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 -8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>
                <path id="outline-bg" opacity="0.05" fill="none" stroke="#565454" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 -8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>
            </svg>
            </div>
        </div>

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
