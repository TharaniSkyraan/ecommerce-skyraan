<div>
    <div class="card">
        <div class="row">
            <div class="col-12">
                <h5>If you have a moment, please let us know why you unsubscribed:</h5>
                <div>
                    <form>
                        <div class="d-flex mt-5">
                            <input type="radio" class="form-check-input" name="reason" wire:model="reason" id="reason1" value="I no longer want to receive these emails">
                            <label for="reason1"> &nbsp; I no longer want to receive these emails</label>
                        </div>
                        <div class="d-flex mt-3">
                            <input type="radio" class="form-check-input" name="reason" wire:model="reason" id="reason2" value="I never signed up for this mailing list">
                            <label for="reason2"> &nbsp; I never signed up for this mailing list</label>
                        </div>
                        <div class="d-flex mt-3">
                            <input type="radio" class="form-check-input" name="reason" wire:model="reason" id="reason3" value="The emails are inappropriate">
                            <label for="reason3"> &nbsp; The emails are inappropriate</label>
                        </div>
                        <div class="d-flex mt-3">
                            <input type="radio" class="form-check-input" name="reason" wire:model="reason" id="reason4" value="The emails are spam and should be reported">
                            <label for="reason4"> &nbsp; The emails are spam and should be reported</label>
                        </div>
                        <div class="d-flex mt-3">
                            <input type="radio" class="form-check-input" name="reason" wire:model="reason" id="reason5" value="other">
                            <label for="reason5"> &nbsp; Other (fill in reason below)</label>
                        </div>
                        @error('reason') <span class="error">{{$message}}</span> @endif
                        <div class="{{ ($reason=='other')?'':'d-none' }} my-3">
                            <label for="reason_note">Reason</label>         
                            <textarea class="form-control mt-1" id="reason_note" placeholder="Reason note" wire:model="reason_note"> </textarea>              
                            @error('reason_note') <span class="error">{{$message}}</span> @endif
                        </div>
                        <div class="mt-4">
                            <button type="button" class="btn px-5" wire:click.prevent="store"><h5 class="text-white py-1 fw-normal">submit</h5></button>
                        </div>                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

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
            window.location.href = '{{ url("/")}}';
        });
    });
</script>
@endpush