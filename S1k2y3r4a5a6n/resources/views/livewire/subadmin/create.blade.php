<div>
    @if(session()->has('message'))
        <div class="alert-success my-2">
            {{session('message')}}
        </div>                
    @endif
    <form autocomplete="off" enctype="multipart/form-data">
        <div class="row">
            <div class="col-8">
                <div class="card mx-2">
                    <input type="hidden" name="subadmin_id" id="subadmin_id" wire:model="subadmin_id"  placeholder="Brand Id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Name" wire:model="name">
                        @error('name') <span class="error"> {{$message}}</span> @endif
                    </div> 
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" placeholder="Email" wire:model="email">
                        @error('email') <span class="error"> {{$message}}</span> @endif
                    </div> 
                    <div class="form-group" >
                        <label for="password" style="width:100%">Password @if(!empty($subadmin_id) && $password_input=='hide')<a href="javascript:void(0)" class="primary float-end" wire:click="changePassword"> Change Password</a>@endif</label>
                        @if($password_input=='show')
                            <input type="text" name="password" id="password" placeholder="Password" wire:model="password">
                        @endif
                        @error('password') <span class="error"> {{$message}}</span> @endif
                    </div>
                    <div class="form-group">
                        <label for="image">Profile Pic
                            @if ($profile_photo_path)
                                <img src="{{ $profile_photo_path->temporaryUrl() }}" alt="Brand-icon" class="my-1 cat-image">
                            @elseif($temp_profile_photo_path)
                            <img src="{{ asset('storage') }}/{{$temp_profile_photo_path}}" alt="Brand-icon" class="my-1 cat-image">
                            @else
                                <img src="{{ asset('admin/images/placeholder.png') }}" alt="Brand-icon" class="my-1 cat-image">
                            @endif
                        </label>
                        <input type="file" name="image" id="image" wire:model="profile_photo_path" accept=".png, .jpg, .jpeg">
                        @error('profile_photo_path') <span class="error"> {{$message}}</span> @endif
                    </div>
                    <div class="form-group mb-4 {{ (\Auth::guard('admin')->user()->id!=$subadmin_id)?'':'d-none'}}">
                        <label for="locationSets">Warehouse</label>
                        <section wire:ignore>
                            <select name="locationSets" id="locationSets" multiple="multiple" placeholder="Filter by...">
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" @if(in_array($warehouse->id,$selected_warehouse_ids)) selected @endif>{{ ucwords($warehouse->address) }}</option>
                                @endforeach
                            </select>
                        </section>
                    </div> 
                    <div class="form-group py-5">
                        <div class="float-end">
                            <a href="{{ route('admin.subadmin.index') }}" class="btn btn-c btn-lg" >Back</a>
                            <button wire:click.prevent="store" class="btn btn-s btn-lg">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 {{ (\Auth::guard('admin')->user()->id!=$subadmin_id)?'':'d-none'}}">
                <div class="card">
                    <h3>Modules</h3><br>
                    <div class="py-3">                    
                        @foreach($modules as $index => $module)
                            @if($module->key !='sub-admin')
                                @if(count($module->sub_modules)==0)                      
                                    <div class="d-flex">
                                        <input type="checkbox" wire:model="privileges.{{ $module->id }}" id="checkbox{{ $module->id }}">
                                        <label for="checkbox{{ $module->id }}"> &nbsp; {{ ucwords($module->module) }}</label>
                                    </div>
                                @else
                                    <label for="" class="fw-bold">{{ ucwords($module->module)}}</label>
                                    @foreach($module->sub_modules as $index => $submodule)
                                        <div class="d-flex mx-4">                                
                                            <input type="checkbox" wire:model="privileges.{{ $submodule->id }}" id="checkbox{{ $submodule->id }}">
                                            <label for="checkbox{{ $submodule->id }}"> &nbsp; {{ ucwords($submodule->module) }}</label>
                                        </div>                            
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://s.cdpn.io/55638/selectize.min.0.6.9.js"></script> 
<script>
    var selector = $('#locationSets');
    $(document).ready(function () {    
        selector.selectize({
            plugins: ['remove_button'],
            onChange: function(value) {               
                @this.set('selected_warehouse_ids', value);
            }
        });
    });
</script>
@endpush