var initiated = true;
window.addEventListener("DOMContentLoaded", function () {
    if(initiated){
        var screenWidth = window.innerWidth;   
        if(screenWidth<=991){         
            Livewire.emit("screenWidthUpdated", screenWidth);
        }
        var urlParams = new URLSearchParams(window.location.search);
        if(urlParams.size!=0){
            Livewire.emit("InitiateFilters");
        }else{
            
            Livewire.emit('disbaleLoader');
        }
        initiated = false;
    }
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
});