$(document).ready(function () {

   const expense = window.lang.expense;
   const expVal = expense.validation;
   const expPopup = expense.popup;

   // allow only numbers in amount field
   $('#expense_amount').on('keypress', function (e) {
      if (e.which < 48 || e.which > 57) {
         return false;
      }
   });

   // show other expense type
   $('#expense_type_id').on('change', function () {
      const expense_type_id = $(this).val();
      if (expense_type_id == '999') {
         $('#other_expense_type_div').show();
      } else {
         $('#other_expense_type_div').hide();
         $('#other_expense_type').val('');
      }

      // reset Expense Name section
      $('#other_expense_div').hide();
      $('#other_expense_name').val('');

      $('#expense_name_id').empty();
      $('#expense_name_id').append(
         $('<option></option>').val('').text(expense.lables_select_expense)
      );

      if (expense_type_id === '') {
         return false;
      }
      $.ajax({
         type: "POST",
         url: $('#expense_list_url').val(),
         data: {
            _token: $('input[name="_token"]').val(),
            expense_type_id: expense_type_id
         },

         success: function (response) {
            $.each(response, function (key, value) {
               $('#expense_name_id').append(
                  $('<option></option>').val(value.id).text(value.expense_name)
               );
            });
            $('#expense_name_id').append($('<option></option>').val(999).text(expense.lables_others));
         }
      });
   });

   $('#expense_name_id').on('change', function () {
      if ($(this).val() == '999') {
         $('#other_expense_div').show();
      } else {
         $('#other_expense_div').hide();
         $('#other_expense_name').val('');
      }
   });
   
   // form validation while click the exp-submit-btn
   $('.exp-submit-btn').on('click', function () {

      clearErrors();

      let isValid = true;

      // values
      const project_type_id = $('#project_type_id').val();
      const expense_type_id = $('#expense_type_id').val();
      const expense_name_id = $('#expense_name_id').val();
      const expense_amount = $('#expense_amount').val().trim();
      const expense_date = $('#expense_date').val();
      const vendor_name = $('#vendor_name').val().trim();
      const bill_no = $('#bill_no').val().trim();
      const expense_description = $('#expense_description').val().trim();
      const remarks = $('#remarks').val().trim();

      // expense type and expense name other text box
      const other_expense_type = $('#other_expense_type').val().trim();
      const other_expense_name = $('#other_expense_name').val().trim();

      // validation
      if (project_type_id === '') {
         showError('#project_type_id', expVal.project_type_id.required);
         isValid = false;
      }

      if (expense_type_id === '') {
         showError('#expense_type_id', expVal.expense_type_id.required);
         isValid = false;
      }
      if (expense_type_id == '999' && other_expense_type === '') {
         showError('#other_expense_type', expVal.other_expense_type.required);
         isValid = false;
      } else if (other_expense_type !== '' && other_expense_type.length < 3) {
        showError('#other_expense_type', expVal.other_expense_type.min);
        isValid = false;
      } else if (other_expense_type !== '' && other_expense_type.length > 150) {
         showError('#other_expense_type', expVal.other_expense_type.max);
         isValid = false;
      }

      if (expense_name_id === '') {
         showError('#expense_name_id', expVal.expense_name_id.required);
         isValid = false;
      }
      if (expense_name_id == '999' && other_expense_name === '') {
         showError('#other_expense_name', expVal.other_expense_name.required);
         isValid = false;
      } else if (other_expense_name !== '' && other_expense_name.length < 3) {
        showError('#other_expense_name', expVal.other_expense_name.min);
        isValid = false;
      } else if (other_expense_name !== '' && other_expense_name.length > 150) {
         showError('#other_expense_name', expVal.other_expense_name.max);
         isValid = false;
      }

      if (expense_amount === '') {
         showError('#expense_amount', expVal.expense_amount.required);
         isValid = false;
      } else if (isNaN(expense_amount)) {
         showError('#expense_amount', expVal.expense_amount.numeric);
         isValid = false;
      } else if (Number(expense_amount) < 1) {
         showError('#expense_amount', expVal.expense_amount.min);
         isValid = false;
      } else if (Number(expense_amount) > 150000) {
         showError('#expense_amount', expVal.expense_amount.max);
         isValid = false;
      }

      const selectedDate = new Date(convertDate(expense_date));
      selectedDate.setHours(0, 0, 0, 0);

      const today = new Date();
      today.setHours(0, 0, 0, 0);

      if (expense_date === '') {
         showError('#expense_date', expVal.expense_date.required);
         isValid = false;
      } else if (!isValidDate(expense_date)) {
         showError('#expense_date', expVal.expense_date.invalid);
         isValid = false;
      } else if (selectedDate.getTime() > today.getTime()) {
         showError('#expense_date', expVal.expense_date.before_or_equal);
         isValid = false;
      }

      if (expense_description.length > 255) {
         showError('#expense_description', expVal.expense_description.max);
         isValid = false;
      }

      if (vendor_name.length > 100) {
         showError('#vendor_name', expVal.vendor_name.max);
         isValid = false;
      }

      if (bill_no.length > 50) {
         showError('#bill_no', expVal.bill_no.max);
         isValid = false;
      }

      if (remarks.length > 255) {
         showError('#remarks', expVal.remarks.max);
         isValid = false;
      }
      if (!isValid) {
         return false;
      }

      Swal.fire({
         title: expPopup.common.title,
         text: expPopup.create.text,
         icon: "warning",
         showCancelButton: true,
         confirmButtonText: expPopup.common.confirm_button,
         cancelButtonText: expPopup.common.cancel_button
      }).then((result) => {
         if (result.isConfirmed) {
            $('#expense_register_form').submit();
         }
      });
   });
});

function showError(selector, message) {
   const el = $(selector);
   el.addClass('is-invalid');
   let errorId = '';

   switch (selector) {
      case '#project_type_id':
         errorId = '#error_project_type_id';
         break;

      case '#expense_type_id':
         errorId = '#error_expense_type_id';
         break;

      case '#expense_name_id':
         errorId = '#error_expense_name_id';
         break;

      case '#expense_amount':
         errorId = '#error_expense_amount';
         break;

      case '#expense_date':
         errorId = '#error_expense_date';
         break;

      case '#vendor_name':
         errorId = '#error_vendor_name';
         break;

      case '#bill_no':
         errorId = '#error_bill_no';
         break;

      case '#expense_description':
         errorId = '#error_expense_description';
         break;

      case '#remarks':
         errorId = '#error_remarks';
         break;

      case '#other_expense_type':
         errorId = '#error_other_expense_type';
         break;

      case '#other_expense_name':
         errorId = '#error_other_expense_name';
         break;
   }

   $(errorId).text(message);
}

function clearErrors() {
   $('.is-invalid').removeClass('is-invalid');

   $('#error_project_type_id').text('');
   $('#error_expense_type_id').text('');
   $('#error_expense_name_id').text('');
   $('#error_expense_amount').text('');
   $('#error_expense_date').text('');
   $('#error_vendor_name').text('');
   $('#error_bill_no').text('');
   $('#error_expense_description').text('');
   $('#error_remarks').text('');

   $('#error_other_expense_type').text('');
   $('#error_other_expense_name').text('');
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

    return (
        d.getFullYear() === year &&
        d.getMonth() === month &&
        d.getDate() === day
    );
}