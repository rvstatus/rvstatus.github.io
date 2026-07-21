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
        const date_of_birth = $('#date_of_birth').val().trim();
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
        if (date_of_birth === '') {
            showError('#date_of_birth', empVal.date_of_birth.required);
            isValid = false;
        } else if (!isValidDate(date_of_birth)) {
            showError('#date_of_birth', empVal.date_of_birth.invalid);
            isValid = false;
        } else if (!isAdult(date_of_birth)) {
            showError('#date_of_birth', empVal.date_of_birth.age);
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
        } else if (!isValidDate(join_date)) {
            showError('#join_date', empVal.join_date.invalid);
            isValid = false;
        } else if (new Date(convertDate(join_date)) > new Date(new Date().setHours(0, 0, 0, 0))) {
            showError('#join_date', empVal.join_date.before_or_equal);
            isValid = false;
        } else if (date_of_birth !== '' && new Date(convertDate(join_date)) < new Date(convertDate(date_of_birth))) {
            showError('#join_date', empVal.join_date.after_or_equal);
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
        case '#date_of_birth':
            errorId = '#error_date_of_birth';
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

function convertDate(date) {
    let parts = date.split('/');
    return parts[2] + '-' + parts[1] + '-' + parts[0];
}

function isValidDate(date) {
    const regex = /^\d{2}\/\d{2}\/\d{4}$/;
    if (!regex.test(date)) {
        return false;
    }

    let parts = date.split('/');
    let day = parseInt(parts[0]);
    let month = parseInt(parts[1]) - 1;
    let year = parseInt(parts[2]);
    let d = new Date(year, month, day);

    return ( d.getFullYear() === year && d.getMonth() === month && d.getDate() === day );
}

function isAdult(date) {
    let dob = new Date(convertDate(date));
    let today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    let monthDiff = today.getMonth() - dob.getMonth();

    if ( monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate()) ) {
        age--;
    }

    return age >= 18;
}