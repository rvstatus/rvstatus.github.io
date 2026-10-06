// FORM MODES (create or edit) 
const MODE_CREATE = 1;
const MODE_EDIT = 2;
// default mode is create
let formMode = MODE_CREATE;

// toggle project type active / inactive with confirmation dialog
function submitToggle(project_type_id)
{
    // set selected id in hidden input
    $('#project_type_id').val(project_type_id);
    // confirmation popup
    Swal.fire({
        title: lang.project_type.popup.common.title,
        text: lang.project_type.popup.toggle.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: lang.project_type.popup.common.confirm_button,
        cancelButtonText: lang.project_type.popup.common.cancel_button
    }).then((result) => {
        if (result.isConfirmed) {
            // submit form if confirmed
            $('#toggleForm').submit();
        }
    });
}

// open modal in CREATE mode
function openCreateModal()
{
    formMode = MODE_CREATE;
    // set modal title and button for create
    $('#modalTitle').text(lang.project_type.form.add_title);
    $('#submitBtnText').text(lang.project_type.form.add_button);
    $('#submitBtn i').attr('class', 'fas fa-save');
    // clear fields
    $('#project_type_hidden_id').val('');
    $('#project_type_name').val('');
    // show modal
    $('#projectTypeModal').modal('show');
}

// open modal in EDIT mode
function openEditModal(id, name)
{
    formMode = MODE_EDIT;
    // set modal title and button for edit
    $('#modalTitle').text(lang.project_type.form.edit_title);
    $('#submitBtnText').text(lang.project_type.form.edit_button);
    $('#submitBtn i').attr('class', 'fas fa-edit');
    // fill existing data
    $('#project_type_hidden_id').val(id);
    $('#project_type_name').val(name);
    // show modal
    $('#projectTypeModal').modal('show');
}

// validate and submit form (create or update)
function submitProjectTypeForm()
{
    // clear previous error
    $('#error_name').text('');
    let name = $('#project_type_name').val().trim();
    // validation checks
    if(name === ''){
        $('#error_name').text(lang.project_type.validation.name.required);
        return false;
    } else if(name.length < 5){
        $('#error_name').text(lang.project_type.validation.name.min);
        return false;
    } else if(name.length > 50){
        $('#error_name').text(lang.project_type.validation.name.max);
        return false;
    } else {
        $('#error_name').text('');
        // set confirmation message based on mode
        let confirmText = (formMode === MODE_EDIT) ? lang.project_type.popup.update.text : lang.project_type.popup.create.text;
        // confirmation popup
        Swal.fire({
            title: lang.project_type.popup.common.title,
            text: confirmText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: lang.project_type.popup.common.confirm_button,
            cancelButtonText: lang.project_type.popup.common.cancel_button
        }).then((result) => {
            if (result.isConfirmed) {
            // set form action based on mode
            let actionUrl = (formMode === MODE_EDIT) ? "../expense/project_type_update": "../expense/project_type_register";
            // submit form
            $('#projectTypeForm').attr('action', actionUrl);
            $('#projectTypeForm').submit();
            }
        });
    }
}

// close modal and reset form
function closeProjectTypeModal()
{
    // reset input field
    $('#project_type_name').val('');
    // reset hidden id
    $('#project_type_hidden_id').val('');
    // clear error message
    $('#error_name').text('');
    // close modal
    $('#projectTypeModal').modal('hide');
}