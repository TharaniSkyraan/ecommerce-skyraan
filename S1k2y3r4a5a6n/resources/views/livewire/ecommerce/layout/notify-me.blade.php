<div>
    @if(!(\Auth::check()) && empty($success))
        <div class="d-flex justify-content-between m-4">
            <div>
                <h6>Email when stock available</h6>
            </div>
            <div>
                <img src="{{asset('asset/home/close_popup.svg')}}" alt="close" data-bs-dismiss="modal" aria-label="Close">
            </div>
        </div>
    @endif
    <div class="modal-bodys p-4">
        @if(!(\Auth::check()) && empty($success))
            <form>
                <div class="pb-3">
                    <input type="email" class="form-control" placeholder="Email" wire:model="email">
                    @error('email') <span class="error"> {{$message}}</span> @endif
                </div>
                <button class="btn px-xl-5 px-lg-5 px-sm-5 px-md-5 px-4 text-white py-2 w-100">
                    <h6 class="fw-bold" wire:click.prevent="NotifyMe">Submit</h6>
                </button>
            </form>       
        @endif
        @if(!empty($success))                     
            <div class="text-center">
                <img src="{{asset('asset/home/notify.svg')}}" alt="notify" class="mb-3">
                <h6>We will inform you through mail once <br> the  product available</h6>
            </div>
        @endif
    </div>
</div>
@push('scripts')
<script>

</script>
@endpush