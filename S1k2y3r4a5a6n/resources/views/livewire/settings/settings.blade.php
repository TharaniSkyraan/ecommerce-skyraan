<div>
    @if(session()->has('message'))
        <div class="alert-success my-2">
            {{session('message')}}
        </div> 
    @endif
    <div class="tab-container">
        <div class="tab {{ ($tab=='general')?'active-tab':'' }}" data-linkid="general">General</div>
        <div class="tab {{ ($tab=='notification')?'active-tab':'' }}" data-linkid="notification">Notification's</div>
        <div class="tab {{ ($tab=='shipment')?'active-tab':'' }}" data-linkid="shipment">Shipping & Payment </div>
        <div class="tab {{ ($tab=='why_choose')?'active-tab':'' }}" data-linkid="why_choose">Why Choose</div>
    </div>
    <div id="general" class="tab-content pt-5 {{ ($tab=='general')?'active-content':'' }}">
        @include('admin.settings.general-setting')
    </div>
    <div id="notification" class="tab-content pt-5 {{ ($tab=='notification')?'active-content':'' }}">
        @if($tab=='notification')
            @include('admin.settings.notification-setting')
        @endif
    </div>
    <div id="shipment" class="tab-content pt-5 {{ ($tab=='shipment')?'active-content':'' }}">
        @if($tab=='shipment')  
            @include('admin.settings.shipment')   
        @endif
    </div>
    <div id="why_choose" class="tab-content pt-5 {{ ($tab=='why_choose')?'active-content':'' }}">
        @include('admin.settings.why_choose')   
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).on('click','.tab', function()
    {
        @this.set('tab', $(this).attr('data-linkid'));
    });
    $(document).on('click','.ClearFavIcon', function()
    {
        var temp_image = '{{ $temp_fav_icon }}';
        if(temp_image==''){
            $('#faviconPreview').attr('src',"{{ asset('admin/images/placeholder.png') }}");
        }else{
            $('#faviconPreview').attr('src',"{{ asset('storage') }}/"+temp_image);
        }
    });
    // JavaScript code to handle the color picker and manual input
    const colorPicker = document.getElementById('colorPicker');
    const hexInput = document.getElementById('hexInput');

    // Set initial color value for both color picker and input box
    const initialColor = '{{ $theme_primary_color }}'; // Example initial color (red)
    colorPicker.value = initialColor;
    hexInput.value = initialColor;

    // Update color picker and input box when color is selected
    colorPicker.addEventListener('input', function(event) {
        const selectedColor = event.target.value; // Get the selected color
        hexInput.value = selectedColor; // Update input box with selected color
    });

    // Update color picker and input box when color is entered manually
    hexInput.addEventListener('input', function(event) {
        const hexColor = event.target.value; // Get the entered hex color

        // Validate hex color format
        const hexRegex = /^#?([0-9A-F]{3}|[0-9A-F]{6})$/i;
        if (hexRegex.test(hexColor)) {
            colorPicker.value = hexColor; // Update color picker with entered color
            @this.set('theme_primary_color', hexColor);
        }
    });

    // JavaScript code to handle the color picker and manual input
    const colorPicker1 = document.getElementById('colorPicker1');
    const hexInput1 = document.getElementById('hexInput1');

    // Set initial color value for both color picker and input box
    const initialColor1 = '{{ $theme_secondary_color }}'; // Example initial color (red)
    colorPicker1.value = initialColor1;
    hexInput1.value = initialColor1;

    // Update color picker and input box when color is selected
    colorPicker1.addEventListener('input', function(event) {
        const selectedColor1 = event.target.value; // Get the selected color
        hexInput1.value = selectedColor1; // Update input box with selected color
    });

    // Update color picker and input box when color is entered manually
    hexInput1.addEventListener('input', function(event) {
        const hexColor1 = event.target.value; // Get the entered hex color

        // Validate hex color format
        const hexRegex1 = /^#?([0-9A-F]{3}|[0-9A-F]{6})$/i;
        if (hexRegex1.test(hexColor1)) {
            colorPicker1.value = hexColor1; // Update color picker with entered color
            @this.set('theme_secondary_color', hexColor1);
        }
    });

    // JavaScript code to handle the color picker and manual input
    const colorPicker2 = document.getElementById('colorPicker2');
    const hexInput2 = document.getElementById('hexInput2');

    // Set initial color value for both color picker and input box
    const initialColor2 = '{{ $theme_tertiary_color }}'; // Example initial color (red)
    colorPicker2.value = initialColor2;
    hexInput2.value = initialColor2;

    // Update color picker and input box when color is selected
    colorPicker2.addEventListener('input', function(event) {
        const selectedColor2 = event.target.value; // Get the selected color
        hexInput2.value = selectedColor2; // Update input box with selected color
    });

    // Update color picker and input box when color is entered manually
    hexInput2.addEventListener('input', function(event) {
        const hexColor2 = event.target.value; // Get the entered hex color

        // Validate hex color format
        const hexRegex2 = /^#?([0-9A-F]{3}|[0-9A-F]{6})$/i;
        if (hexRegex2.test(hexColor2)) {
            colorPicker2.value = hexColor2; // Update color picker with entered color
            @this.set('theme_tertiary_color', hexColor2);
        }
    });

</script>