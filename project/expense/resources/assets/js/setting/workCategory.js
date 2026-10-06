// FORM MODES (create or edit) 
const MODE_CREATE = 1;
const MODE_EDIT = 2;
// default mode is create
let formMode = MODE_CREATE;

// toggle work category active / inactive with confirmation dialog
function submitToggle(work_category_id)
{
    $('#work_category_id').val(work_category_id);
    Swal.fire({
        title: lang.work_category.popup.common.title,
        text: lang.work_category.popup.toggle.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: lang.work_category.popup.common.confirm_button,
        cancelButtonText: lang.work_category.popup.common.cancel_button
    }).then((result) => {
        if (result.isConfirmed) {
            $('#toggleForm').submit();
        }
    });
}

// open modal in CREATE mode
function openCreateModal()
{
    formMode = MODE_CREATE;
    $('#modalTitle').text(lang.work_category.form.add_title);
    $('#submitBtnText').text(lang.work_category.form.add_button);
    $('#submitBtn i').attr('class', 'fas fa-save');
    $('#work_category_hidden_id').val('');
    $('#work_category_name').val('');
    $('#workCategoryModal').modal('show');
}

// open modal in EDIT mode
function openEditModal(id, name)
{
    formMode = MODE_EDIT;
    $('#modalTitle').text(lang.work_category.form.edit_title);
    $('#submitBtnText').text(lang.work_category.form.edit_button);
    $('#submitBtn i').attr('class', 'fas fa-edit');
    $('#work_category_hidden_id').val(id);
    $('#work_category_name').val(name);
    $('#workCategoryModal').modal('show');
}

// validate and submit form
function submitWorkCategoryForm()
{
    $('#error_name').text('');
    let name = $('#work_category_name').val().trim();

    if(name === ''){
        $('#error_name').text(lang.work_category.validation.name.required);
        return false;
    } else if(name.length < 5){
        $('#error_name').text(lang.work_category.validation.name.min);
        return false;
    } else if(name.length > 50){
        $('#error_name').text(lang.work_category.validation.name.max);
        return false;
    } else {
        $('#error_name').text('');

        let confirmText = (formMode === MODE_EDIT) ? lang.work_category.popup.update.text : lang.work_category.popup.create.text;

        Swal.fire({
            title: lang.work_category.popup.common.title,
            text: confirmText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: lang.work_category.popup.common.confirm_button,
            cancelButtonText: lang.work_category.popup.common.cancel_button
        }).then((result) => {
            if (result.isConfirmed) {
                let actionUrl = (formMode === MODE_EDIT) ? "../expense/work_category_update": "../expense/work_category_register";
                $('#workCategoryForm').attr('action', actionUrl);
                $('#workCategoryForm').submit();
            }
        });
    }
}

// close modal
function closeWorkCategoryModal()
{
    $('#work_category_name').val('');
    $('#work_category_hidden_id').val('');
    $('#error_name').text('');
    $('#workCategoryModal').modal('hide');
}