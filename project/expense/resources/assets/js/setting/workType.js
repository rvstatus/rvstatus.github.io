// FORM MODES (create or edit) 
const MODE_CREATE = 1;
const MODE_EDIT = 2;
// default mode is create
let formMode = MODE_CREATE;

// toggle work type active / inactive
function submitToggle(work_type_id)
{
    $('#work_type_id').val(work_type_id);

    Swal.fire({
        title: lang.work_type.popup.common.title,
        text: lang.work_type.popup.toggle.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: lang.work_type.popup.common.confirm_button,
        cancelButtonText: lang.work_type.popup.common.cancel_button
    }).then((result) => {
        if (result.isConfirmed) {
            $('#toggleForm').submit();
        }
    });
}

// open create modal
function openCreateModal()
{
    formMode = MODE_CREATE;

    $('#modalTitle').text(lang.work_type.form.add_title);
    $('#submitBtnText').text(lang.work_type.form.add_button);
    $('#submitBtn i').attr('class', 'fas fa-save');

    $('#work_type_hidden_id').val('');
    $('#work_type_name').val('');

    $('#workTypeModal').modal('show');
}

// open edit modal
function openEditModal(id, name)
{
    formMode = MODE_EDIT;

    $('#modalTitle').text(lang.work_type.form.edit_title);
    $('#submitBtnText').text(lang.work_type.form.edit_button);
    $('#submitBtn i').attr('class', 'fas fa-edit');

    $('#work_type_hidden_id').val(id);
    $('#work_type_name').val(name);

    $('#workTypeModal').modal('show');
}

// submit form
function submitWorkTypeForm()
{
    $('#error_name').text('');
    $('#error_category').text('');

    let name = $('#work_type_name').val().trim();

    if(name === ''){
        $('#error_name').text(lang.work_type.validation.name.required);
        return false;
    } else if(name.length < 5){
        $('#error_name').text(lang.work_type.validation.name.min);
        return false;
    } else if(name.length > 50){
        $('#error_name').text(lang.work_type.validation.name.max);
        return false;
    } else {

        let confirmText = (formMode === MODE_EDIT)
            ? lang.work_type.popup.update.text
            : lang.work_type.popup.create.text;

        Swal.fire({
            title: lang.work_type.popup.common.title,
            text: confirmText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: lang.work_type.popup.common.confirm_button,
            cancelButtonText: lang.work_type.popup.common.cancel_button
        }).then((result) => {
            if (result.isConfirmed) {
                let actionUrl = (formMode === MODE_EDIT)
                    ? "../expense/work_type_update"
                    : "../expense/work_type_register";

                $('#workTypeForm').attr('action', actionUrl);
                $('#workTypeForm').submit();
            }
        });
    }
}

// close modal
function closeWorkTypeModal()
{
    $('#work_type_name').val('');
    $('#work_type_hidden_id').val('');
    $('#error_name').text('');
    $('#error_category').text('');
    $('#workTypeModal').modal('hide');
}