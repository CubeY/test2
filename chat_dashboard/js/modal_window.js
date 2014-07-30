$(function() {
     initModalWindow();
});

// Initializes the window
function initModalWindow() {
    var id = "#modal_dialog";

    var maskHeight = $(document).height(); 
    var maskWidth = $(window).width();
        
    $('#modal_mask').css({'width':maskWidth,'height':maskHeight});

    var winH = $(window).height();
    var winW = $(window).width();
        
    $("#modal_dialog").css('top', winH/2-$(id).height()/2); 
    $("#modal_dialog").css('left', winW/2-$(id).width()/2); 

    // Window is resized
    $(window).resize(function () { 
        var box = $('#modal_box.modal_window');
        var maskWidth = $(window).width();
        var maskHeight = $(document).height();
        $('#modal_mask').css({'width':maskWidth,'height':maskHeight});
        var winH = $(window).height(); 
        var winW = $(window).width();
        box.css('top', winH/2 - box.height()/2); 
        box.css('left', winW/2 - box.width()/2); 
    });
}

// Opens a modal window, filling the content with the passed html string
function openModalWindow(html) {
   allowBackgroundScroll(false);
   showModalWindow(true);
   $("#modal_content").html(html);   
}

// Closes a modal window
function closeModalWindow() {
    allowBackgroundScroll(true);
    showModalWindow(false);
}

// Open/close window with animation
function showModalWindow(show) {
    if (show) {
        $('#modal_mask').fadeIn(0); 
        $('#modal_mask').fadeTo("slow",0.5);
        $("#modal_dialog").fadeIn(0);

        // Make sure screen always opens at the top 
        document.getElementById('modal_content').parentNode.scrollTop =0;
    } else {
        $('#modal_mask').hide(); 
        $('.modal_window').hide(); 
    }
}

// Enable/disable mouse scrolling of the main window
function allowBackgroundScroll(allow) {
    if (allow) {
        $("html,body").css("overflow","auto");
    } else {
        $("html,body").css("overflow","hidden");
    }
}
