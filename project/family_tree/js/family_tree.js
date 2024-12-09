$(document).ready(function() {
    // // disable the developer tool on right click
    // document.oncontextmenu = function() { return false; };

    // // disable F12 key OR Ctrl+Shift+I OR Ctrl+Shift+J OR Ctrl+U combo
    // document.addEventListener('keydown', event => {
    //   if (event.keyCode === 123 
    //     || (event.ctrlKey && event.shiftKey && event.keyCode === 73) 
    //     || (event.ctrlKey && event.shiftKey && event.keyCode === 74)
    //     || (event.ctrlKey && event.keyCode === 85)) {
    //     event.preventDefault();
    //   }
    // });

    // refers to scroll window into left
    var lastScrollLeft = 0;
    var lastScrollTop = 0;
    $(window).scroll(function() {
        if(my_popup_div != null) {
            my_popup_div.hide();
        }
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

        // calculate the date as per the given details
        var dob_value = $(this).children("div.popup_box").find('.popup_box_table_td_dob').eq(0).text();
        var dod_value = $(this).children("div.popup_box").find('.popup_box_table_td_dod').eq(0).text();
        var lc_value = $(this).children("div.popup_box").find('.popup_box_table_td_lc').eq(0).text();
        var age_value = $(this).children("div.popup_box").find('.popup_box_table_td_age').eq(0).text();
        var dod = dod_value;
        if(dod_value == "alive") {
            dod = get_current_date();
        }
        var start = parseDate(dob_value);
        var end = parseDate(dod);
        var lc = calculate_life_cycle(start,end,dod_value);
        var age = calculate_age(start, end); // birthdate and end date
        $(this).children("div.popup_box").find('.popup_box_table_td_lc').eq(0).text(lc);
        $(this).children("div.popup_box").find('.popup_box_table_td_age').eq(0).text(age);
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

// get the current date
function get_current_date() {
    var current_date = new Date();
    var formatted_date = format_date(current_date);
    return formatted_date;
}

// function to format date in dd-mm-yyyy format
function format_date(date) {
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    return day + '-' + month + '-' + year;
}

// function to parse a date in "DD-MM-YYYY" format
function parseDate(dateStr) {
    var parts = dateStr.split('-');
    return new Date(parts[2], parts[1] - 1, parts[0]);
}

// calculate difference in years, months, and days
function calculate_life_cycle(start, end, dod_value) {

    var years = end.getFullYear() - start.getFullYear();
    var months = end.getMonth() - start.getMonth();
    var days = end.getDate() - start.getDate();

    // adjust months and years if necessary
    if (days < 0) {
        months--;
        days += new Date(end.getFullYear(), end.getMonth(), 0).getDate();
    }

    if (months < 0) {
        years--;
        months += 12;
    }

    var start_year = start.getFullYear();
    var end_year = end.getFullYear();
    if(dod_value == "alive") {
        end_year = "present"
    }

    // var lc_str = " ( " + years + " years, " + months + " months, " + days + " days )";
    var lc_str = start_year +" - "+ end_year +" ( "+ years +" years, " + months + " months, " + days + " days )";
    return lc_str;
}

// calculate age
function calculate_age(startDate, endDate) {
    var startDateObj = new Date(startDate);
    var endDateObj = new Date(endDate);
    
    var age = endDateObj.getFullYear() - startDateObj.getFullYear();

    var monthDifference = endDateObj.getMonth() - startDateObj.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && endDateObj.getDate() < startDateObj.getDate())) {
        age--;
    }

    return age;
}
