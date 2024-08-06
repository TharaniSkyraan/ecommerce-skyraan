<!DOCTYPE html>
<html lang="en">
<head>
    @include('common.meta')
    @include('common.top_css')
    @yield('customstyle')

</head>
<body>

    @include('common.navbar')
    @yield('content')
    @include('common.footer')
</body>
    @include('common.bottom_script')
    @yield('customscript')
</html>
