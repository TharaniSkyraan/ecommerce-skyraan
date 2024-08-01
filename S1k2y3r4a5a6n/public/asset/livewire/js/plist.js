var initiated = true;
window.addEventListener("DOMContentLoaded", function () {
    if(initiated){
        var screenWidth = window.innerWidth;   
        if(screenWidth<=991){         
            Livewire.emit("screenWidthUpdated", screenWidth);
        }
        var urlParams = new URLSearchParams(window.location.search);
        Livewire.emit("InitiateFilters");
        if(urlParams.size!=0){
            
        }else{
            
            Livewire.emit('disbaleLoader');
        }
        initiated = false;
    }
    
});

document.addEventListener('livewire:load', function () {        
    Livewire.on('categoryUpdated', message => {
        location.reload();
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
});