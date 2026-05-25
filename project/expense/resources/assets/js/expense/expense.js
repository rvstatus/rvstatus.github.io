$( document ).ready(function() {
    // Working Date on register screen
    $( "#working_date" ).datepicker({
        format: "mm/dd/yyyy",
        weekStart: 0,
        autoclose: true,
        todayHighlight: true
    });
    // Working Hours on register screen
    $('.timepicker').timepicker({
        timeFormat: 'h:mm p',
        interval: 30,
        minTime: '6:00am',
        maxTime: '10:00pm',
        defaultTime: '9:00am',
        startTime: '6:00am',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
});