var initiated = true;
window.addEventListener("DOMContentLoaded", function () {
    if(initiated){
        var screenWidth = window.innerWidth;   
        if(screenWidth<=991){         
            Livewire.emit("screenWidthUpdated", screenWidth);
        }
        var urlParams = new URLSearchParams(window.location.search);
        Livewire.emit("InitiateFilters");
        // if(urlParams.size!=0){
        //     Livewire.emit("InitiateFilters");
        // }else{
        //     Livewire.emit('disbaleLoader');
        // }
        initiated = false;
    }
    
});
document.addEventListener('livewire:load', function () {        
    Livewire.on('TotalRecord', count => {
        $('.search_result_count').html(count);
    });
});
$(document).ready(function() {
    $(window).scroll(function() {  
        if($('#load-more').html()){
            var length = $('.PrdRow').length;
            var check = length - 1;
            var targetDivs = $('.PrdRow').eq(check);
            targetDivs.each(function() {
                var targetDiv = $(this);
                var targetOffset = targetDiv.offset().top;
                if ($(window).scrollTop() + $(window).height() >= targetOffset && $('.load').html()=='false') {
                    Livewire.emit('loadMore');
                    $('.load').html('true');
                }
            });
        }
    });
    
    $(document).on('click','.likedislike', function()
    {
        if($(this).attr('data-id')=='unlike'){
            $(this).attr('src', '../../asset/home/like.svg');
            $(this).attr('data-id','like');
        }else{
            $(this).attr('src', '../../asset/home/like-filled.svg');
            $(this).attr('data-id','unlike');
        }
        id = $(this).closest('.PrdRow').data('id');
        Livewire.emit('addremoveWish', id);
    });
});