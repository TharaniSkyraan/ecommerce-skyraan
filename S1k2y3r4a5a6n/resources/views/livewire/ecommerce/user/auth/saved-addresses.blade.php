
<div class="p-3" > 
    @if($isProcessing) 
        <div id="loader" wire:loading></div>
    @endif
    <h6 class="text-center">Address Detail</h6>
    <div class="px-xl-5 px-lg-5 px-md-4 px-sm-3 px-0 pt-3" @if($isProcessing) wire:loading.class="opacity-0" @endif>
        <div class="py-1">
            <input type="text" class="form-control" placeholder="Name" wire:model="name">                    
            @error('name') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1">
            <div class="d-flex" wire:ignore>
                <span class="dial_code">+91</span>
                <input type="number" oninput="isNumberKey(event)" class="form-control phonenumber_dial_code" placeholder="Phone number" wire:model="phone">
            </div>                 
            @error('phone') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1">
            <div class="d-flex" wire:ignore>
                <span class="dial_code">+91</span>
                <input type="number" oninput="isNumberKey(event)" class="form-control phonenumber_dial_code" placeholder="Alternative Phone number" wire:model="alternative_phone">
            </div>                 
            @error('alternative_phone') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1">
            <input type="text" class="form-control" placeholder="House, Building, Area" wire:model="address" id="address">  
            @error('address') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1">
            <input type="text" class="form-control" placeholder="Landmark (Optional)" wire:model="landmark">  
            @error('landmark') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1">
            <div class="form-group">
                <select class="form-select" wire:model="state" placeholder="Select">
                    <option>Select</option>
                    @foreach($states as $stat)
                    <option value="{{$stat->name}}">{{$stat->name}}</option>
                    @endforeach
                </select>
            </div>                    
            @error('state') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1">                  
            <input type="text" class="form-control" placeholder="City" wire:model="city">                    
            @error('city') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="py-1 pb-3">
            <input type="number" class="form-control" placeholder="Pincode" wire:model="postal_code" oninput="isNumberKey(event)">                    
            @error('postal_code') <span class="error">{{$message}}</span> @endif
        </div>
        <div class="text-center">
            <div class=" btnss btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-75" wire:click="store"><h6>Save Address</h6></div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{ $siteSetting->google_map_api_key }}"></script> 
<script>
    var searchInput = 'address';
    
    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode']
            
        });
    
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            Livewire.emit('FomatAddress',place.formatted_address);
            // Livewire.emit('FomatAddress',place.geometry.location.lat(),place.geometry.location.lng());
        });
    });
</script>
<script>
   document.addEventListener('livewire:load', function () {
        Livewire.on('showToast', message => {
            toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            }
        
            toastr['success'](message, {
                closeButton: true,
                positionClass: 'toast-top-right',
                progressBar: true,
                newestOnTop: true
            });
            $('.close-btn').trigger('click');
        });
    });
function isNumberKey(event) {
  const input = event.target;
  const value = input.value;

  // Allow empty input
  if (value === '') return;

  // Allow valid number input
  const validNumber = /^[0-9]*\.?[0-9]*$/.test(value);
  if (!validNumber) {
      input.value = value.slice(0, -1);
      return;
  }
}
</script>
@endpush
