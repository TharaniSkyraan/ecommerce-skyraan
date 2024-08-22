<div>
    <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.warehouses.index') }}">Warehouse List </a></li>
    <li>Warehouse</li>
    </ul>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="form-group">
                    <label for="name">Warehouse Name</label>
                    <input type="text" name="name" id="name" placeholder="Ex:Tamil nadu" wire:model="name">
                    @error('name') <span class="error"> {{$message}}</span> @endif
                </div>   
                <div class="form-group">
                    <label for="ware_house_address">Address</label>
                    <input type="text" name="ware_house_address" id="ware_house_address" placeholder="Ex:Tamil nadu" wire:model="ware_house_address">
                    @error('ware_house_address') <span class="error"> {{$message}}</span> @endif
                </div>   
                <div class="form-group mb-2">
                    <label for="locationSets">Sub admin</label>
                    <section wire:ignore>
                        <select name="locationSets" id="locationSets" multiple="multiple" placeholder="Filter by...">
                            @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" @if(in_array($admin->id,$admin_ids)) selected @endif>{{ ucwords($admin->name) }} - {{ ucwords($admin->email) }}</option>
                            @endforeach
                        </select>
                    </section>
                   
                </div> 
                <div class="form-group mb-2">
                    <label for="zoneSets">Zone</label>
                    <section wire:ignore>
                        <select name="zoneSets" id="zoneSets" multiple="multiple" placeholder="Filter by...">
                            @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" @if(in_array($zone->id,$selected_zone_ids)) selected @endif>{{ ucwords($zone->zone) }}</option>
                            @endforeach
                        </select>
                    </section>
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
                <div class="form-group py-5 action-btn">
                    <div class="float-end">
                        <div id="button-container">
                            <button class="btn btn-s btn-lg" id="submit" wire:click.prevent="store">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?&key=AIzaSyC5S9f4bqHOjf0DP3yeL1C32t0S609fUQM&libraries=drawing,places"></script>
<script src="https://s.cdpn.io/55638/selectize.min.0.6.9.js"></script> 
<script>
    var selector = $('#locationSets');
    var zoneselector = $('#zoneSets');
    var searchInput = 'ware_house_address';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode']
        });
    
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            @this.set('lat', place.geometry.location.lat());
            @this.set('lng', place.geometry.location.lng());
            @this.set('ware_house_address', place.formatted_address);
        });
        
        selector.selectize({
            plugins: ['remove_button'],
            onChange: function(value) {               
                @this.set('admin_ids', value);
            }
        });
        zoneselector.selectize({
            plugins: ['remove_button'],
            onChange: function(value) {               
            @this.set('selected_zone_ids', value);
          }
        });
    });
    // Get the full URL
    var url = window.location.href;
    // Split the URL by '/' and get the last segment
    var segments = url.split('/');
    var lastSegment = segments.pop() || segments.pop();  // Handle trailing slash
    if(lastSegment !='edit' && lastSegment !='create'){
        $('input, select, textarea').prop('disabled', true);
        $('.action-btn').html('');
    }
</script>
@endpush