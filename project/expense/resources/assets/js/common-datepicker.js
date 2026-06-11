$(document).ready(function () {
    flatpickr(".datepicker", {
        dateFormat: "d/m/Y",
        allowInput: true
    });
    
    // flatpickr(".timepicker", {
    //     enableTime: true,
    //     noCalendar: true,
    //     dateFormat: "H:i",
    //     time_24hr: true
    // });
});