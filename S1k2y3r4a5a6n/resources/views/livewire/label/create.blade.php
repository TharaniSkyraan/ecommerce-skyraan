<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <input type="hidden" name="label_id" id="label_id" wire:model="label_id"  placeholder="Label Id">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="Label Name" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>         
                <div class="form-group mb-4">
                    <label for="name">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="color">Color</label>
                    <div class="d-flex color-picker" wire:ignore>
                        <input type="color" class="b-none" id="colorPicker" placeholder="Color Code" wire:model="color">
                        <input type="text" class="b-none" id="hexInput" placeholder="Enter HEX color">
                    </div>
                    @error('color') <span class="error"> {{$message}}</span> @endif
                </div>                
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ route('admin.label.index') }}" class="btn btn-c btn-lg" >Back</a>
                        <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // JavaScript code to handle the color picker and manual input
    const colorPicker = document.getElementById('colorPicker');
    const hexInput = document.getElementById('hexInput');

    // Set initial color value for both color picker and input box
    const initialColor = '{{ $color }}'; // Example initial color (red)
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
            @this.set('color', hexColor);
        }
    });

</script>