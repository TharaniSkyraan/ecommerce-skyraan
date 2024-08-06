<form autocomplete="off" enctype="multipart/form-data">
    <div class="row">
        <div class="col-8">
            <div class="card mx-2">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" name="site_name" id="site_name" placeholder="Site Name" wire:model="site_name">
                    @error('site_name') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="cat_site_logo" class="cursor-pointer">Site Logo 
                        @if ($site_logo)
                            <img src="{{ $site_logo->temporaryUrl() }}" alt="site_logo" class="my-1 cat-image">
                        @elseif($temp_site_logo)
                            <img src="{{ asset('storage') }}/{{$temp_site_logo}}" alt="site_logo" class="my-1 cat-image">
                        @else
                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="site_logo" class="my-1 cat-image">
                        @endif
                    </label>
                    @if ($site_logo)
                        <div class="d-flex">
                            <span class="btn btn-d btn-sm cursor-pointer" wire:click="RemoveUploaded('site_logo')">Remove</span>
                        </div>
                    @endif
                    <input type="file" name="site_logo" id="cat_site_logo" wire:model="site_logo" accept=".png, .jpg, .jpeg" class="d-none">
                    @error('site_logo') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="cat_fav_icon" class="cursor-pointer" wire:ignore>Fav iocn 
                        @if($temp_fav_icon)
                            <img src="{{ asset('storage') }}/{{$temp_fav_icon}}" alt="site_fav_icon" class="my-1 cat-image" id="faviconPreview">
                        @else
                            <img src="{{ asset('admin/images/placeholder.png') }}" alt="site_fav_icon" class="my-1 cat-image" id="faviconPreview">
                        @endif
                    </label>
                    @if ($fav_icon)
                        <div class="d-flex">
                            <span class="btn btn-d btn-sm cursor-pointer ClearFavIcon">Remove</span>
                        </div>
                    @endif
                    <span class="btn b-none">The favicon must be a file of type/extension ".ico"</span><br>
                    <input type="file" name="fav_icon" id="cat_fav_icon" wire:model="fav_icon" accept=".ico" class="d-none">
                    @error('fav_icon') <span class="error"> {{$message}}</span> @endif
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <div class="d-flex" wire:ignore>
                        <span class="dial_code">+91</span>
                        <input type="text" class="form-control phonenumber_dial_code" placeholder="Phone number" wire:model="phone">
                    </div>
                    @error('phone') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="mail_from_name">Mail from name</label>
                    <input type="text" name="mail_from_name" id="mail_from_name" placeholder="Mail from name" wire:model="mail_from_name">
                    @error('mail_from_name') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="mail_from_address">From email address</label>
                    <input type="email" name="mail_from_address" id="mail_from_address" placeholder="From email" wire:model="mail_from_address">
                    @error('mail_from_address') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="mail_to_name">Mail to name</label>
                    <input type="text" name="mail_to_name" id="mail_to_name" placeholder="mail to name" wire:model="mail_to_name">
                    @error('mail_to_name') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="mail_to_address">To email address</label>
                    <input type="email" name="mail_to_address" id="mail_to_address" placeholder="To email" wire:model="mail_to_address">
                    @error('mail_to_address') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="mail_support_name">Mail support name</label>
                    <input type="text" name="mail_support_name" id="mail_support_name" placeholder="mail support name" wire:model="mail_support_name">
                    @error('mail_support_name') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="mail_support_address">Support email address</label>
                    <input type="email" name="mail_support_address" id="mail_support_address" placeholder="Support email" wire:model="mail_support_address">
                    @error('mail_support_address') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" placeholder="Address" wire:model="address"></textarea>
                    @error('address') <span class="error"> {{$message}}</span> @endif
                </div> 
                <div class="form-group">
                    <label for="footer_content">Footer Content</label>
                    <textarea class="form-control" placeholder="Footer content" wire:model="footer_content"></textarea>
                    @error('footer_content') <span class="error"> {{$message}}</span> @endif
                </div> 
                <h4 class="mt-5 mb-2">Theme Color</h4>                                   
                <hr>                        
                <div class="form-group mt-3" >
                    <label for="color">Primary Color</label>
                    <div class="d-flex color-picker" wire:ignore>
                        <input type="color" class="b-none" id="colorPicker" placeholder="Color Code" wire:model="theme_primary_color">
                        <input type="text" class="b-none" id="hexInput" placeholder="Enter HEX color">
                    </div>
                    @error('theme_primary_color') <span class="error"> {{$message}}</span> @endif
                </div>           
                <div class="form-group" >
                    <label for="color">Secondary Color</label>
                    <div class="d-flex color-picker" wire:ignore>
                        <input type="color" class="b-none" id="colorPicker1" placeholder="Color Code" wire:model="theme_secondary_color">
                        <input type="text" class="b-none" id="hexInput1" placeholder="Enter HEX color">
                    </div>
                    @error('theme_secondary_color') <span class="error"> {{$message}}</span> @endif
                </div>           
                <div class="form-group" >
                    <label for="color">Tertiary Color</label>
                    <div class="d-flex color-picker" wire:ignore>
                        <input type="color" class="b-none" id="colorPicker2" placeholder="Color Code" wire:model="theme_tertiary_color">
                        <input type="text" class="b-none" id="hexInput2" placeholder="Enter HEX color">
                    </div>
                    @error('theme_tertiary_color') <span class="error"> {{$message}}</span> @endif
                </div>     
                <div class="form-group py-5">
                    <div class="float-end">
                        <a href="{{ url('admin/settings') }}?tab=general" class="btn btn-c btn-lg" >Reset</a>
                        <button wire:click.prevent="storegeneral" class="btn btn-s btn-lg">Submit</button>
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