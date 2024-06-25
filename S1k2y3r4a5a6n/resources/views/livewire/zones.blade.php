<div>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
        #button-container {
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
               
    <ul class="breadcrumb">
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{ route('admin.zones.index') }}">Delivery Zone List </a></li>
    <li>Delivery Zone</li>
    </ul>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="form-group">
                    <label for="zone">Zone Name</label>
                    <input type="text" name="zone" id="zone" placeholder="Ex:Tamil nadu" wire:model="zone">
                    @error('zone') <span class="error"> {{$message}}</span> @endif
                </div>    
                <div class="form-group">
                    <div class="float-end">
                        <div id="button-container">
                            <button class="btn btn-s btn-lg" id="clear-button" wire:ignore>Clear</button>
                        </div>
                    </div>
                </div>
                <div wire:ignore>
                    <div id="map"></div>
                </div>
                @error('zone_coordinates') <span class="error"> {{$message}}</span> @endif
                    
                <div class="form-group mb-4">
                    <label for="name">Status</label>
                    <select name="status" id="status" wire:model="status">
                        <option value="">Select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group py-5">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?&key=AIzaSyC5S9f4bqHOjf0DP3yeL1C32t0S609fUQM&libraries=drawing,places"></script> 
<script>
    var searchInput = 'zone';
    
    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode']
            
        });
    
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            @this.set('lat', place.geometry.location.lat());
            @this.set('lng', place.geometry.location.lng());
            @this.set('zone', place.formatted_address);
        });
    });
</script>
<script>
    $(document).ready(function () {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: { lat: 24.89896961896874, lng: 73.50104129262246 }
        });

        // Predefined polygon coordinates
        var predefinedCoords = JSON.parse(@json($coordinates));
        
        // Create a polygon with predefined coordinates
        if(predefinedCoords.length!=0){  
            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: null,
                drawingControl: false,
                polygonOptions: {
                    editable: true,
                    draggable: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                }
            });

            drawingManager.setMap(map);
            
            var polygon;
            polygon = new google.maps.Polygon({
                paths: predefinedCoords,
                editable: true,
                draggable: true,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map
            });
        }else{ 
            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON, // Set default mode to POLYGON
                drawingControl: false,
                polygonOptions: {
                    editable: true,
                    draggable: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                }
            });

            drawingManager.setMap(map);

            polygon = new google.maps.Polygon({
                drawingMode: null,
                drawingControl: false,
                editable: true,
                draggable: true,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map
            });
            $('#clear-button').toggle();

        }
        
        // Function to calculate center point of a polygon
        function calculatePolygonCenter(polygon) {
            var bounds = new google.maps.LatLngBounds();
            polygon.getPath().forEach(function (point) {
                bounds.extend(point);
            });
            return bounds.getCenter();
        }

        // Function to update map center based on polygon
        function updateMapCenter() {
            var centerPoint = calculatePolygonCenter(polygon);
            map.setCenter(centerPoint);
        }
            
        updateMapCenter();

        // Event listener for clear button
        $('#clear-button').click(function () {
            // Clear the existing polygon if any
            if (polygon) {
                polygon.setMap(null);
            }
            // Set drawing mode to POLYGON
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
            $('#clear-button').toggle();
        });

        // Event listener for polygon edit
        google.maps.event.addListener(polygon.getPath(), 'insert_at', updateCoordinates);
        google.maps.event.addListener(polygon.getPath(), 'remove_at', updateCoordinates);
        google.maps.event.addListener(polygon.getPath(), 'set_at', updateCoordinates);

        function updateCoordinates() {
            var predefinedCoords = [];
            polygon.getPath().forEach(function (latLng, index) {
                predefinedCoords[index] = { lat: latLng.lat(), lng: latLng.lng() };
            });
            @this.set('zone_coordinates', predefinedCoords);
        }

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (poly) {
            if (polygon) {
                // Remove the previously drawn polygon
                polygon.setMap(null);
            }
            polygon = poly;
            // Disable drawing mode after completing the first polygon
            drawingManager.setDrawingMode(null);
            updateCoordinates();
            
            $('#clear-button').toggle();
        });

    });
</script>
@endpush