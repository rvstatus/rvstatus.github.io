$(document).ready(function () {
    // allow only numbers in mobile number field
    $('#mobile_no').on( 'keypress', function (e) {
        if ( e.which < 48 || e.which > 57 ) {
            return false;
        }
    });
    // initialize join date picker
    $('#join_date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
    });
});