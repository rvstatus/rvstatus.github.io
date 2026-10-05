$(document).ready(function () {
    // edit button click
    $('#btn_edit').on('click', function () {
        $('#employee_form').attr('action', $(this).data('action')).submit();
    });

    // delete button click
    $('#btn_delete').on('click', function () {
        Swal.fire({
            title: window.lang.employee.popup.common.title,
            text: window.lang.employee.popup.delete.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: window.lang.employee.popup.common.confirm_button,
            cancelButtonText: window.lang.employee.popup.common.cancel_button
        }).then((result) => {
            if (result.isConfirmed) {
                $('#employee_form').attr('action', $(this).data('action')).submit();
            }
        });
    });

    // delete revert button click
    $('#btn_delete_revert').on('click', function () {
        let action = $(this).data('action');
        let confirmRequired = $(this).data('confirm');
        Swal.fire({
            title: window.lang.employee.popup.common.title,
            text: window.lang.employee.popup.revert_delete.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: window.lang.employee.popup.common.confirm_button,
            cancelButtonText: window.lang.employee.popup.common.cancel_button
        }).then((result) => {
            if (result.isConfirmed) {
                $('#employee_form').attr('action', action).submit();
            }
        });
    });

});