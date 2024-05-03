$(document).ready(function() {
    // disable the developer tool on right click
    document.oncontextmenu = function() { return false; };

    // disable F12 key OR Ctrl+Shift+I OR Ctrl+Shift+J OR Ctrl+U combo
    document.addEventListener('keydown', event => {
      if (event.keyCode === 123 
        || (event.ctrlKey && event.shiftKey && event.keyCode === 73) 
        || (event.ctrlKey && event.shiftKey && event.keyCode === 74)
        || (event.ctrlKey && event.keyCode === 85)) {
        event.preventDefault();
      }
    });

    // refers to scroll window into left
    var lastScrollLeft = 0;
    var lastScrollTop = 0;
    $(window).scroll(function() {
        my_popup_div.hide();
        var documentScrollLeft = $(document).scrollLeft();
        if (lastScrollLeft != documentScrollLeft) {
            lastScrollLeft = documentScrollLeft;
        }
        var documentScrollTop = $(document).scrollTop();
        if (lastScrollTop != documentScrollTop) {
            lastScrollTop = documentScrollTop;
        }
    });
    var my_popup_div = null;
    $(".profile_pic_img").on("click", function(evt) {
        // returns height of browser viewport
        // var windowHeight = $(window).height();
        // alert(windowHeight);
        // alert(evt.pageY);
        // alert((windowHeight / 1.5 ));
        // if((windowHeight / 1.5 ) < evt.pageY) {
        //     alert("before : "+lastScrollTop);
        //     lastScrollTop = evt.pageY-windowHeight;
        //     alert("after : "+lastScrollTop);
        // }
        $(this).children("div.popup_box")
            .css({
                display: "block",
                left: evt.pageX-lastScrollLeft,
                top: evt.pageY-lastScrollTop+20
            }
        );
        my_popup_div = $(this).children("div.popup_box");
    });

    $(document).mouseup(function(e) {
        // if the target of the click isn't the my_popup_div nor a descendant of the my_popup_div
        if(my_popup_div != null) {
            if (!my_popup_div.is(e.target) && my_popup_div.has(e.target).length === 0) {
                my_popup_div.hide();
            }
        }
    });

    // $(document).mousedown(function(e) {
    //     if( e.button == 2 ) {
    //         // alert($(this).attr('title'));
    //     }
    //     return true;
    // });

    // double click time preview the image
    $(".profile_pic_img img").dblclick(function () {
        my_popup_div.hide();
        // set the src,alt of the image clicked into lightbox anchor and image tag
        $(".lightbox_tag img").attr("src",$(this).attr("src"));
        $(".lightbox_tag img").attr("alt",$(this).attr("alt"));

        $(".lightbox_tag").attr("href",$(this).attr("src"));
        $(".lightbox_tag").attr("data-title",$(this).attr("alt"));

        if($(".lightbox_tag").attr("href") != "") {
            // trigger the lightbox_tag
            $(".lightbox_tag").trigger("click");

            // empty the src,alt of the image clicked into lightbox anchor and image tag
            $(".lightbox_tag img").attr("src","");
            $(".lightbox_tag img").attr("alt","");

            $(".lightbox_tag").attr("href","");
            $(".lightbox_tag").attr("data-title","");
        }
    });
});