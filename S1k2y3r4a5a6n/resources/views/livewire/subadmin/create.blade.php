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
            <div class="col-4 {{ (\Auth::guard('admin')->user()->id!= $subadmin_id)?'':'d-none'}}">
                <div class="card">
                    <h3>Modules</h3><br>
                    <div class="py-3" id="card"> 
                        @foreach($modules as $index => $module)
                            @if($module->key !='sub-admin')
                                @if(count($module->sub_modules)==0)
                                    <div class="d-flex">
                                        <input type="checkbox" wire:model="privileges.{{ $module->id }}" id="checkbox{{ $module->id }}" class="parentModule">
                                        <label for="checkbox{{ $module->id }}"> &nbsp; {{ ucwords($module->module) }}</label>
                                    </div>
                                    <div class="{{ (isset($privileges[$module->id]))?'':'d-none'}} px-4 mx-4 checkbox{{ $module->id }}">
                                        <div class="row action">
                                            @if($module->add ==1)
                                            <div class="col-3">
                                                <div class="d-flex">
                                                    <input type="checkbox" wire:model="privileges.{{ $module->id }}.add" id="checkbox-add{{ $module->id }}">
                                                    <label for="checkbox-add{{ $module->id }}"> &nbsp; Add</label>
                                                </div>
                                            </div>
                                            @endif
                                            @if($module->edit ==1)
                                            <div class="col-3">
                                                <div class="d-flex">
                                                    <input type="checkbox" wire:model="privileges.{{ $module->id }}.edit" id="checkbox-edit{{ $module->id }}">
                                                    <label for="checkbox-edit{{ $module->id }}"> &nbsp; Edit</label>
                                                </div>
                                            </div>
                                            @endif
                                            @if($module->view ==1)
                                            <div class="col-3">
                                                <div class="d-flex">
                                                    <input type="checkbox" wire:model="privileges.{{ $module->id }}.view" id="checkbox-view{{ $module->id }}">
                                                    <label for="checkbox-view{{ $module->id }}"> &nbsp; View</label>
                                                </div>
                                            </div>
                                            @endif
                                            @if($module->delete ==1)
                                            <div class="col-3">
                                                <div class="d-flex">
                                                    <input type="checkbox" wire:model="privileges.{{ $module->id }}.delete" id="checkbox-delete{{ $module->id }}">
                                                    <label for="checkbox-delete{{ $module->id }}"> &nbsp; Delete</label>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <label for="" class="fw-bold">{{ ucwords($module->module)}}</label>
                                    @foreach($module->sub_modules as $index => $submodule)
                                        <div class="d-flex mx-4">                                
                                            <input type="checkbox" wire:model="privileges.{{ $submodule->id }}" id="checkbox{{ $submodule->id }}" class="subModule">
                                            <label for="checkbox{{ $submodule->id }}"> &nbsp; {{ ucwords($submodule->module) }}</label>
                                        </div>  
                                        <div class="{{ (isset($privileges[$submodule->id]))?'':'d-none'}} px-4 mx-4 checkbox{{ $submodule->id }}">
                                            @if($submodule->key == 'manage-stock')
                                                <div class="row action">
                                                    <div class="col-4">
                                                        <div class="d-flex">
                                                            <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.upload" id="checkbox-upload{{ $submodule->id }}">
                                                            <label for="checkbox-upload{{ $submodule->id }}"> &nbsp; Upload</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex">
                                                            <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.transfer" id="checkbox-transfer{{ $submodule->id }}">
                                                            <label for="checkbox-transfer{{ $submodule->id }}"> &nbsp; Transfer</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex">
                                                            <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.modify" id="checkbox-modify{{ $submodule->id }}">
                                                            <label for="checkbox-modify{{ $submodule->id }}"> &nbsp; Modify</label>
                                                        </div>
                                                    </div>
                                                </div> 
                                            @else
                                            <div class="row action">
                                                    @if($submodule->add ==1)
                                                        <div class="col-3">
                                                            <div class="d-flex">
                                                                <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.add" id="checkbox-add{{ $submodule->id }}">
                                                                <label for="checkbox-add{{ $submodule->id }}"> &nbsp; Add</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($submodule->edit ==1)
                                                        <div class="col-3">
                                                            <div class="d-flex">
                                                                <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.edit" id="checkbox-edit{{ $submodule->id }}">
                                                                <label for="checkbox-edit{{ $submodule->id }}"> &nbsp; Edit</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($submodule->view ==1)
                                                        <div class="col-3">
                                                            <div class="d-flex">
                                                                <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.view" id="checkbox-view{{ $submodule->id }}">
                                                                <label for="checkbox-view{{ $submodule->id }}"> &nbsp; View</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($submodule->delete ==1)
                                                        <div class="col-3">
                                                            <div class="d-flex">
                                                                <input type="checkbox" wire:model="privileges.{{ $submodule->id }}.delete" id="checkbox-delete{{ $submodule->id }}">
                                                                <label for="checkbox-delete{{ $submodule->id }}"> &nbsp; Delete</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
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