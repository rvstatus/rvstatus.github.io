var cancel_check = true;
var old_selected_day = "";

$('input, select, textarea').on('change keyup paste', function() {
  cancel_check = false;
});

function div_popup_close() {

  if (cancel_check) {
    $("body div").removeClass("modalOverlay");
    $('#empselectionpopup').empty();
    $('#empselectionpopup').modal('toggle');
  } else {
    if (confirm(window.lang.salary.popup.cancel.text)) {
      cancel_check = true;
      $("body div").removeClass("modalOverlay");
      $('#empselectionpopup').empty();
      $('#empselectionpopup').modal('toggle');
    }
  }
}

function on_change_salary_day_select_box(day) {
  if (!cancel_check) {
    if (!confirm(window.lang.salary.popup.day_change.text)) {
        // restore previous selected day
        $("#day").val(old_selected_day);
      return false;
    }
  }
  cancel_check = true;
  var year = $("#year").val();
  var month = $("#month").val();
  $('#empselectionpopup').load( '../salary/empselectionpopup?year=' + year + '&month=' + month + '&day=' + day );
}

function store_old_day() {
    old_selected_day = $("#day").val();
}