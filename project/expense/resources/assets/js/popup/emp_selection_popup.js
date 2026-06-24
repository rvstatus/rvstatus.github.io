
  var cancel_check = true;

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