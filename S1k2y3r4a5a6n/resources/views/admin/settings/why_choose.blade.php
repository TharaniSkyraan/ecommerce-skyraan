<form autocomplete="off" enctype="multipart/form-data">
            <div class="row">
                <div class="col-8">
                    <div class="card mx-2">
                        <div class="form-group">
                            <label for="why_chs_title"> Title</label>
                            <input type="text" name="why_chs_title" id="why_chs_title" placeholder="Title" wire:model="why_chs_title">
                            @error('why_chs_title') <span class="error"> {{$message}}</span> @endif
                        </div> 

                        <div class="form-group">
                            <label for="why_chs_desc">Description</label>
                            <textarea name="why_chs_desc" id="why_chs_desc" placeholder="Discription" wire:model="why_chs_desc"></textarea>
                            @error('why_chs_desc') <span class="error"> {{$message}}</span> @endif
                        </div> 

                        <div class="form-group">
                            <label for="why_chs_img" class="cursor-pointer">Image 
                                @if ($why_chs_img)
                                    <img src="{{ $why_chs_img->temporaryUrl() }}" alt="why_chs_img" class="my-1 cat-image">
                                @elseif($why_chs_img)
                                    <img src="{{ asset('storage') }}/{{$why_chs_img}}" alt="site_logo" class="my-1 cat-image">
                                @else
                                    <img src="{{ asset('admin/images/placeholder.png') }}" alt="why_chs_img" class="my-1 cat-image">
                                @endif
                            </label>
                            @if ($why_chs_img)
                                <div class="d-flex">
                                    <span class="btn btn-d btn-sm cursor-pointer" wire:click="removeUploaded('why_chs_img')">Remove</span>
                                </div>
                            @endif
                            <input type="file" name="why_chs_img" id="why_chs_img" wire:model="why_chs_img" accept=".png, .jpg, .jpeg" class="d-none">
                            @error('why_chs_img') <span class="error"> {{$message}}</span> @endif
                        </div>

                        <div class="form-group py-5">
                            <div class="float-end">
                                <a href="{{ url('admin/settings') }}?tab=general" class="btn btn-c btn-lg" >Reset</a>
                                <button wire:click.prevent="storewhychoose" class="btn btn-s btn-lg">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            
            const faviconInput = document.getElementById('cat_fav_icon');
            const faviconPreview = document.getElementById('faviconPreview');

            faviconInput.addEventListener('change', function() {
                const file = faviconInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        faviconPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>