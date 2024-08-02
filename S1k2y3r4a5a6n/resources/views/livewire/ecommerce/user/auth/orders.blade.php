<div>
    <section class="userdashboard">
        <div class="container">
            <div class="row select_lists">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 select-li select_list ps-xl-3 ps-lg-3 ps-md-2 ps-sm-0 ps-0 pe-xl-3 pe-lg-3 pe-md-2 pe-sm-0 pe-0 pb-3" wire:ignore>
                    @include('ecommerce.user.auth.sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 order-li pb-3 my-order collg9">
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 col-4 d-flex gap-3 align-items-center">
                            <div class="row w-100">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 pe-0">
                                    <h6 class="fw-bold">My Orders</h6> 
                                </div>
                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12 px-xl-0 system-view">
                                    <div class="d-flex gap-xl-2 gap-lg-2 gap-md-2 gap-sm-2 gap-2 justify-content-center tab-head">
                                        <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='all')?'activated':''}}" data-tab="all">Orders Placed</span>
                                        <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='buy-again')?'activated':''}}" data-tab="buy-again">Buy Again</span>
                                        <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='shipped')?'activated':''}}" data-tab="shipped" >Shipping In progress</span>
                                        <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='cancelled')?'activated':''}}" data-tab="cancelled">Cancelled Orders</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-8 d-flex justify-content-end gap-1 align-items-center">
                           @if($tab=='all') 
                                <div><span class="fw-bold h-sm">{{ $total_orders }} orders </span><span class="h-sm">placed</span></div>
                                <!-- <select class="form-select h-sm cursor" aria-label="">
                                    <option value="all" class="h-sm">Last Month</option>
                                    <option value="created_at-ascending cursor" class="h-sm">Last 3 Months</option>
                                    <option value="created_at-descending cursor" class="h-sm">2023</option>
                                </select> -->
                                <div>
                                    <div class="card rounded-1 py-1 px-2 filter-select position-relative cursor" >
                                        <div class=" d-flex justify-content-between gap-2">
                                            <p class="h-sm">{{ ucwords(str_replace('_',' ',$sort_by)) }}</p>
                                            <img src="{{asset('/asset/home/down-ar.svg')}}" alt="" >
                                        </div>
                                    </div>
                                    <div class="card filter-dropdown rounded-0 border-0 position-absolute jewfjjkdszx"  style="display:none;">
                                        <div class="bg-white py-2">
                                            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='last_30_days')?'selected':'')}}" wire:click="sortByUpdate('last_30_days')">Last 30 days</p>
                                            <p class="px-2 py-1 h-sms cursor {{(($sort_by=='past_3_months')?'selected':'')}}" wire:click="sortByUpdate('past_3_months')">Past 3 months</p>
                                            @foreach($dates as $date)
                                            <p class="px-2 py-1 h-sms cursor {{(($sort_by == $date['year'])?'selected':'')}}"  wire:click="sortByUpdate({{$date['year']}})">{{ $date['year'] }}</p>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div>
                        <div class="tab-mbl-views pt-3">
                            <div class="d-flex gap-xl-2 gap-lg-2 gap-md-2 gap-sm-2 gap-2 justify-content-md-center justify-content-sm-center tab-head">
                                <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='all')?'activated':''}}" data-tab="all">Orders Placed</span>
                                <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='buy-again')?'activated':''}}" data-tab="buy-again">Buy Again</span>
                                <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='shipped')?'activated':''}}" data-tab="shipped" >Shipping In progress</span>
                                <span class="h-sm p-1 cursor text-nowrap tab {{ ($tab=='cancelled')?'activated':''}}" data-tab="cancelled">Cancelled Orders</span>
                            </div>
                        </div>
                    </div>
                    <div class="load d-none">{{$pageloading}}</div>
                    <div class="page d-none">{{$page}}</div>
                    <div id="all" class="tab-content {{($tab=='all'||$tab=='shipped')?'active':''}}">
                        @if($tab=='all'||$tab=='shipped')
                            <div class="row py-3">
                                <div class="col-12">
                                    @include('livewire.ecommerce.user.orders.orders',$orders)
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="buy-again" class="tab-content {{($tab=='buy-again')?'active':''}}">
                        @if($tab=='buy-again')
                            <div class="row py-3">
                                <div class="col-12">
                                    @include('livewire.ecommerce.user.orders.buy_again')
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="cancelled" class="tab-content {{($tab=='cancelled')?'active':''}}">
                        @if($tab=='cancelled')
                            <div class="row py-3">
                                <div class="col-12">
                                    @include('livewire.ecommerce.user.orders.cancelled')
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function(){
    $(".tab-head span").click(function(){
        @this.set('tab', $(this).attr('data-tab'));
    });
});

$(document).ready(function() {
    $(window).scroll(function() {  
        if($('#load-more').html()){
            var length = $('.OrdRow').length;
            var check = length - 1;
            var targetDivs = $('.OrdRow').eq(check);
            targetDivs.each(function() {
                var targetDiv = $(this);
                var targetOffset = targetDiv.offset().top;
                if ($(window).scrollTop() + $(window).height() >= targetOffset && $('.load').html()=='false') {
                    @this.set('page', parseInt($('.page').html())+1);
                    $('.load').html('true');
                    Livewire.emit('loadMore');
                }
            });
        }
    });
});
document.addEventListener('livewire:load', function () {    
    Livewire.on('OpenCancelRequestModel', message => {
        $('#cancel-order').modal('show');
    });
    Livewire.on('OrderCancelSuccessfully', data => {
        $('#cancel-order').modal('hide');
        $('#cancellord_'+data).closest('.OrdRow').find('.shipmentstatus').html('Cancelled');
        $('#cancellord_'+data).closest('.OrdRow').find('.shipmentstatus').addClass('text-danger');
        $('#cancellord_'+data).remove();
        $('#submit-cancel-order').modal('show');            
        setTimeout(function(){ 
            $('#submit-cancel-order').modal('hide')
        }, 3000);
    });
});
</script>
@endpush