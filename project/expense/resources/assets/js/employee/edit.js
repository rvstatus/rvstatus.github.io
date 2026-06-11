$(document).ready(function () {
    // allow only numbers in mobile number field
    $('#mobile_no').on( 'keypress', function (e) {
        if ( e.which < 48 || e.which > 57 ) {
            return false;
        }
    });
    // initialize join date picker
    // $('#join_date').datepicker({
    //     format: 'dd/mm/yyyy',
    //     autoclose: true,
    //     todayHighlight: true
    // });
    const empVal = window.lang.employee.validation;
    const empPopup = window.lang.employee.popup;
    // clear button click
    $('#btn_clear').on('click', function () {
        clearErrors();
        $('#emp_name').val('');
        $('input[name="gender"]').prop('checked', false);
        $('#mobile_no').val('');
        $('#email').val('');
        $('#address').val('');
        $('#category_id').val('');
        $('#join_date').val('');
        $('#salary').val('');
    });

    // back button click
    $('#btn_back').on('click', function () {
        var detail_url = $(this).data('url');
        $('form').attr('action', detail_url);
        $('form').submit();
    });

    // edot button click
    $('#btn_edit').on('click', function (e) {
        // var detail_url = $(this).data('url');
        // $('form').attr('action', detail_url);
        // $('form').submit();

        e.preventDefault();

        clearErrors();

        let isValid = true;

        // values
        const emp_name = $('#emp_name').val().trim();
        const gender = $('input[name="gender"]:checked').val();
        const mobile_no = $('#mobile_no').val().trim();
        const email = $('#email').val().trim();
        const address = $('#address').val().trim();
        const category_id = $('#category_id').val();
        const join_date = $('#join_date').val().trim();
        const salary = $('#salary').val().trim();

        // validation
        if (emp_name === '') {
            showError('#emp_name', empVal.emp_name.required);
            isValid = false;
        } else if (emp_name.length < 3) {
            showError('#emp_name', empVal.emp_name.min);
            isValid = false;
        } else if (emp_name.length > 50) {
            showError('#emp_name', empVal.emp_name.max);
            isValid = false;
        } 
        if (!gender) {
            showError('input[name="gender"]', empVal.gender.required);
            isValid = false;
        } 
        if (mobile_no === '') {
            showError('#mobile_no', empVal.mobile_no.required);
            isValid = false;
        } else if (!/^[0-9]{10}$/.test(mobile_no)) {
            showError('#mobile_no', empVal.mobile_no.invalid);
            isValid = false;
        } 
        if (email === '') {
            showError('#email', empVal.email.required);
            isValid = false;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            showError('#email', empVal.email.invalid);
            isValid = false;
        } 
        if (address === '') {
            showError('#address', empVal.address.required);
            isValid = false;
        } 
        if (category_id === '') {
            showError('#category_id', empVal.category_id.required);
            isValid = false;
        } 
        if (join_date === '') {
            showError('#join_date', empVal.join_date.required);
            isValid = false;
        } 
        if (salary === '') {
            showError('#salary', empVal.salary.required);
            isValid = false;
        } else if (isNaN(salary)) {
            showError('#salary', empVal.salary.invalid);
            isValid = false;
        }

        if (!isValid) {
            return false;
        }

        Swal.fire({
            title: empPopup.common.title,
            text: empPopup.update.text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: empPopup.common.confirm_button,
            cancelButtonText: empPopup.common.cancel_button
        }).then((result) => {
            if (result.isConfirmed) {
                $('form').submit();
            }
        });
    });
});

function showError(selector, message) {
    const el = $(selector);
    el.addClass('is-invalid');
    let errorId = '';
    switch (selector) {
        case '#emp_name':
            errorId = '#error_emp_name';
            break;
        case 'input[name="gender"]':
            errorId = '#error_gender';
            break;
        case '#mobile_no':
            errorId = '#error_mobile_no';
            break;
        case '#email':
            errorId = '#error_email';
            break;
        case '#address':
            errorId = '#error_address';
            break;
        case '#category_id':
            errorId = '#error_category_id';
            break;
        case '#join_date':
            errorId = '#error_join_date';
            break;
        case '#salary':
            errorId = '#error_salary';
            break;
    }
    $(errorId).text(message);
}

function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('#error_emp_name').text('');
    $('#error_gender').text('');
    $('#error_mobile_no').text('');
    $('#error_email').text('');
    $('#error_address').text('');
    $('#error_category_id').text('');
    $('#error_join_date').text('');
    $('#error_salary').text('');
}