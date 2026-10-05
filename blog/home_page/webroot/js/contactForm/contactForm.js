jQuery(document).ready(function($) {
  "use strict";

  // contact form submit process
  $('form.contactForm').submit(function() {

    $("#sendmessage").removeClass("show");

    var f = $(this).find('.form-group'), ferror = false;
    var emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

    f.children('input').each(function() { 
      // loop the all inputs
      var i = $(this); // current input set into i
      var rule = i.attr('data-rule');

      if (rule !== undefined) {
        var ierror = false; // error flag for current input
        var pos = rule.indexOf(':', 0);
        if (pos >= 0) {
          var exp = rule.substr(pos + 1, rule.length);
          rule = rule.substr(0, pos);
        } else {
          rule = rule.substr(pos + 1, rule.length);
        }

        switch (rule) {
          case 'required':
            if (i.val() === '') {
              ferror = ierror = true;
            }
            break;

          case 'minlen':
            if (i.val().length < parseInt(exp)) {
              ferror = ierror = true;
            }
            break;

          case 'email':
            if (!emailExp.test(i.val())) {
              ferror = ierror = true;
            }
            break;

          case 'checked':
            if (!i.attr('checked')) {
              ferror = ierror = true;
            }
            break;

          case 'regexp':
            exp = new RegExp(exp);
            if (!exp.test(i.val())) {
              ferror = ierror = true;
            }
            break;
        }
        i.next('.validation').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
      }
    });
    f.children('textarea').each(function() { // run the all textarea inputs

      var i = $(this); // current textarea input set into i
      var rule = i.attr('data-rule');

      if (rule !== undefined) {
        var ierror = false; // error flag for current textarea input
        var pos = rule.indexOf(':', 0);
        if (pos >= 0) {
          var exp = rule.substr(pos + 1, rule.length);
          rule = rule.substr(0, pos);
        } else {
          rule = rule.substr(pos + 1, rule.length);
        }

        switch (rule) {
          case 'required':
            if (i.val() === '') {
              ferror = ierror = true;
            }
            break;

          case 'minlen':
            if (i.val().length < parseInt(exp)) {
              ferror = ierror = true;
            }
            break;
        }
        i.next('.validation').html((ierror ? (i.attr('data-msg') != undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
      }
    });
    if (ferror) return false;
    else var str = $(this).serialize();
    $.ajax({
      type: "POST",
      url: "controller/ContactController.php",
      data: str,
      success: function(response) {
        let result = JSON.parse(response);
        if (result.success == true) {
          $("#sendmessage").addClass("show");
          $("#errormessage").removeClass("show");
          $('.contactForm').find("input, textarea").val("");
        } else {
          $(".validation").hide();
          // console.log(result.errors);
          $("#sendmessage").removeClass("show");
          // if (result.errors.name !== 'undefined') {
          //   $("#name").next('.validation').html(result.errors.name);
          //   $("#name").next('.validation').show();
          // } else if (result.errors.email !== 'undefined') {
          //   alert();
          //   $("#email").next('.validation').html(result.errors.email);
          //   $("#email").next('.validation').show();
          // } else if (result.errors.subject !== 'undefined') {
          //   $("#subject").next('.validation').html(result.errors.subject);
          //   $("#subject").next('.validation').show();
          // } else if (result.errors.message !== 'undefined') {
          //   $("#message").next('.validation').html(result.errors.message);
          //   $("#message").next('.validation').show();
          // } else {
            $("#errormessage").addClass("show");
            $('#errormessage').html(result.errors);
          // }
        }
      },
      error: function(xhr, status, error) {
        if (status == 'error') {
          $("#sendmessage").removeClass("show");
          $("#errormessage").addClass("show");
          if(error == 'Not Found') {
            $('#errormessage').html("The requested URL was not found on this server.");
          } else {
            $('#errormessage').html(error);
          }
        } else {
          $("#sendmessage").addClass("show");
          $("#errormessage").removeClass("show");
          $('.contactForm').find("input, textarea").val("");
        }
      }
    });
    return false;
  });

});