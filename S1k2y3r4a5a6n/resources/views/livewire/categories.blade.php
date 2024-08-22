<div class="row">
    <div class="col-4">   
        <div class="card mx-2">
            <div class="my-2 text-end">
                @if(in_array('add',$privileges) || in_array('all',$privileges)) 
                    <button class="btn btn-s" wire:click="addCategory('')"> New Category </button> 
                @endif
            </div>
            
            <ul id="card">
                @forelse($categories as $category)
                    <li id="{{$category->id}}">
                        <div class="data-list-group">
                            <i class="bx bx-list-ul"></i>
                            <span class="text-truncate cursor-pointer editcategory" @if (in_array('edit',$privileges) || in_array('view',$privileges) || in_array('all',$privileges)) wire:click="editCategory({{$category->id}},'category')" @endif>{{$category->name}}</span>
                            <div class="{{($category->id==$category_id || $category->id==$parent_id )? '' : 'd-none' }} ms-auto" >
                                <div class="d-inline-flex action">
                                    @if (in_array('delete',$privileges) || in_array('all',$privileges)) 
                                    <i class="bx bx-trash cursor-pointer delete" data-id="{{$category->id}}"></i>
                                    @endif
                                    @if (in_array('add',$privileges) || in_array('all',$privileges)) 
                                    <i class="bx bx-plus cursor-pointer" wire:click="addCategory({{$category->id}})" title="New Sub Category"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <ul class="data-list-child" id="card{{$category->id}}">
                            @foreach($category->sub_categories as $sub_category)
                            <li id="{{$sub_category->id}}">
                                <div class="data-list-group">
                                    <i class="bx bx-list-ul"></i>
                                    <span class="text-truncate cursor-pointer editcategory" @if (in_array('edit',$privileges) || in_array('view',$privileges) || in_array('all',$privileges)) wire:click="editCategory({{$sub_category->id}},'')" @endif>{{$sub_category->name}}</span> 
                                    @if (in_array('delete',$privileges) || in_array('all',$privileges)) 
                                    <i class="bx bx-trash ms-auto cursor-pointer {{($sub_category->id==$category_id)? '' : 'd-none' }} action delete" data-id="{{$sub_category->id}}"></i>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @empty
                    <li>No Data Found</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-8">     
        <div class="card form py-5">
            @if(session()->has('message'))
                <div class="alert-success">
                    {{session('message')}}
                </div>                
            @endif
            @if($page=='category')
                @include('livewire.category.create')
            @elseif($page=='subcategory')
                @include('livewire.category.create_subcategory',$categories)
            @else
                <div>
                    <div id="card" class="text-center align-content-center">
                        <h4>Category/Sub Category</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
@if(in_array('add',$privileges) || in_array('edit',$privileges) || in_array('all',$privileges)) 
    $( function() {
        $( "#card" ).sortable({
            update: function (event, ui) {
                var ids = $(this).sortable('toArray').toString();
                $.post("{{ route('admin.category.sort') }}", {ids: ids, _method: 'POST', _token: '{{ csrf_token() }}'})
            }
        });
        @foreach($categories as $cat)
        
        $( "#card{{$cat->id}}" ).sortable({
            update: function (event, ui) {
                var ids = $(this).sortable('toArray').toString();
                $.post("{{ route('admin.category.sort') }}", {ids: ids, _method: 'POST', _token: '{{ csrf_token() }}'})
            }
        });
        @endforeach
    });
@endif
// Category and Subcategory click action enable disable
$(document).on('click', '.data-list-group', function (e) {
    $('.action').addClass('d-none');
    $(this).find('.action').removeClass('d-none');
});

$(document).on('click', '.delete', function () {  
    if (confirm('Are you sure! you want to delete?')) {
        Livewire.emit('delete',$(this).data('id'));  
        alert('Deleted Successful');
    }
});

$(document).on('click', '.editcategory', function () {  
    $('html, body').animate({
        scrollTop: $('.breadcrumb').offset().top
    });
});
</script>