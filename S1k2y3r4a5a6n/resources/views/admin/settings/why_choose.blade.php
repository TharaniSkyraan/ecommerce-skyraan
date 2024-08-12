<form autocomplete="off" enctype="multipart/form-data" class="{{ ($why_chs_form)?'':'d-none'}}">
    <div class="card mx-2">
        <div class="row">
            <div class="col-8">
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
                        @elseif($why_chs_temp_image)
                            <img src="{{ asset('storage') }}/{{$why_chs_temp_image}}" alt="site_logo" class="my-1 cat-image">
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
                        <a href="javascript:void(0)" class="btn btn-c btn-lg" wire:click.prevent="resetInputvalues">Cancel</a>
                        <button wire:click.prevent="storewhychoose" class="btn btn-s btn-lg">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="{{ ($why_chs_form)?'d-none':''}}">
    <div class="row" wire:ignore>
        <div class="col-12">
            <div class="card mx-2">
                <div class="float-end"> 
                    <button wire:click="addEditwhychoose('')" class="btn btn-s btn-lg">Create</button>
                </div>
                <div class="table-responsive" >
                    <table id="datatable" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">ID</th>
                                <th class="border-bottom-0">Title</th>
                                <th class="border-bottom-0">Image</th>
                                <th class="border-bottom-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{asset('admin/js/dataTable/jquery.dataTables.min.js')}}"></script>
<script>
    var dataTable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            searching: false,
            scrollX: true,
            "order": [[0, "desc"]],
            ajax: {
                url: '{!! route('admin.fetch.whychoose.data') !!}',
            }, columns: [
                {data: 'id', name: 'id'},
                {data: 'why_chs_title', name: 'why_chs_title'},
                {data: 'why_chs_img', name: 'why_chs_img'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
    });
    
    function delete_whychoose(id) {
        if (confirm('Are you sure! you want to delete?')) {
            $.post("{{ url('admin/whychoose') }}/"+id, {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                if (response == 'ok')
                {
                    dataTable.draw();
                } else
                {
                    alert('Request Failed!');
                }
            });
        }
        
    }
    function edit_whychoose(id) {
        Livewire.emit('addEditwhychoose',id);
    }
    document.addEventListener('livewire:load', function () { 
        Livewire.on('updatedwhyChoose', data  => {
            dataTable.draw();
        });    
    });
</script>