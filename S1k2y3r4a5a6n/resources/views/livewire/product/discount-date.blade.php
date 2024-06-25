
<div class="row">
    <div class="col-3 me-2 my-1">
        <div class="form-group" wire:ignore>
            <label for="discount_start_date">Discount Start</label>
            <input type="text" name="discount_start_date" id="discount_start_date"  placeholder="Discount Start" wire:model="discount_start_date">
        </div>
    </div>
    <div class="col-3 me-2 my-1">
        <div class="form-group" wire:ignore>
            <label for="discount_end_date">Discount End</label>
            <input type="text" name="discount_end_date" id="discount_end_date"  placeholder="Discount End" wire:model="discount_end_date">
        </div>
    </div>
   <script>
    
        flatpickr("#discount_start_date", {
            enableTime: true,
            altInput: true,
            dateFormat: "d-m-Y H:i",
            altFormat:"d-m-Y H:i",
            disableMobile: "true",
            defaultDate:'{{ $discount_start_date??$today }}',
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('discount_start_date', dateStr);
            }
        });   

        flatpickr("#discount_end_date", {
            enableTime: true,
            altInput: true,
            dateFormat: "d-m-Y H:i",
            altFormat:"d-m-Y H:i",
            disableMobile: "true",
            defaultDate:'{{ $discount_end_date }}',
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('discount_end_date', dateStr);
            }
        }); 
    </script>
</div>
