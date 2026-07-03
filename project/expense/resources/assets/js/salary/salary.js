var cancel_check = true;
// this methos is used to show the emp select popup
function show_emp_selection_popup(){
    var mainmenu = $('#mainmenu').val();
    var year = $('#selYear').val();
    var month = $('#selMonth').val();
    $('#empselectionpopup').load('../salary/empselectionpopup?year='+year+'&month='+month);
    $("#empselectionpopup").modal({
            backdrop: 'static',
            keyboard: false
        });
    $('#empselectionpopup').modal('show');
}

// register salary button
function add_salary(selYear, selMonth, actionUrl) {
    $('#page').val('');
    $('#plimit').val(50);
    $('#selMonth').val(selMonth);
    $('#selYear').val(selYear);
    var mainmenu = $('#mainmenu').val();
    $('#salaryEmpForm').attr('action',actionUrl + '?mainmenu='+mainmenu+'&time='+datetime);
    $("#salaryEmpForm").submit();
}

// detail view link click
function fn_get_detail_view(employeeId,mainmenu) {
    $("#empId").val(employeeId);
    $('#salaryEmpForm').attr('action', '../salary/detailView?mainmenu='+mainmenu+'&time='+datetime);
    $("#salaryEmpForm").submit();
}

// salary view link click
function goto_salary_view(employeeId,salaryId) {
    $("#empId").val(employeeId);
    $("#salaryId").val(salaryId);
    $("#mainmenu").val("paySlip_salary");
    $('#salaryEmpForm').attr('action', '../salary/view?mainmenu=paySlip_salary&time='+datetime);
    $("#salaryEmpForm").submit();
}

// move employee selected
function move_selected(from, to) {
	$('#' + from + ' option:selected').remove().appendTo('#' + to);
}

// emp select by popup click
function emp_select_by_popup_click() {
    var length = $("#to option").length;
    if(length==0) {
        Swal.fire({
            icon: 'warning',
            text: window.lang.salary.validation.employee.required,
            confirmButtonText: 'OK'
        });
        return false;
    }
    // Swal.fire({
    //     title: window.lang.salary.popup.common.title,
    //     text: window.lang.salary.popup.employee_selection.text,
    //     icon: 'question',
    //     showCancelButton: true,
    //     confirmButtonText: window.lang.salary.popup.common.confirm_button,
    //     cancelButtonText: window.lang.salary.popup.common.cancel_button
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         $('#to option').prop('selected', true);
    //         $('#from option').prop('selected', true);
    //         document.empselectionform.submit();
    //     }
    // });
    if(confirm(window.lang.salary.popup.employee_selection.text)) {
        $('#to option').prop('selected', true);
        $('#from option').prop('selected', true);
        document.empselectionform.submit();
        return true;
    } else {
        return false;
    }
}

// edit and add screen common method start
// cancel button click
function cancel(viewflg,mainmenu) {
    var formName = "";
    if($("#screenName").val() == 'edit') {
        formName = "#salaryEditForm";
    } else {
        formName = "#salaryAddForm";
    }
    if($("#backScreenName").val() == 'salary_view') {
        viewflg = "view";
        $("#screenName").val('payslip_index');
    } else if ($('#backScreenName').val() == 'salary_detail_view') {
        viewflg = "detailView";
    } else if($("#backScreenName").val() == 'salary_view_form_index') {
        viewflg = "view";
    }
    if (cancel_check == false) {
        if (confirm(window.lang.salary.popup.cancel.text)) {
            if (viewflg == "") {
                window.history.back();
            }
            $(formName).attr('action', viewflg+'?mainmenu='+mainmenu+'&time='+datetime);
            $(formName).submit();
        }
    } else {
        $(formName).attr('action', viewflg+'?mainmenu='+mainmenu+'&time='+datetime);
        $(formName).submit();
    }
}

// only type the number on the text box
function is_number_key(evt) { 
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   return true;
}

// if any value type on screen fn_cancel_check call
function fn_cancel_check() {
    cancel_check = false;
    return cancel_check;
}
// edit and add screen common method end

// register screen add_all Method used to register the data
function add_all(i,msg,count) {
    for (var j= 1; j <= count; j++) {
        var basicSalary = $("#basicSalary"+j).val();
        var insentive = $("#insentive"+j).val();
        var pfAmount = $("#pfAmount"+j).val();
        var esiAmount = $("#esiAmount"+j).val();
        if(basicSalary == "" || basicSalary == 0) {
            alert(window.lang.salary.validation.basic_salary.required);
            $("#basicSalary"+j).focus();
            return false;
        } else if(insentive == "" || insentive == 0) {
            alert(window.lang.salary.validation.insentive.required);
            $("#insentive"+j).focus();
            return false;
        // } else if(pfAmount == "" || pfAmount == 0) {
        //     alert(err_pf_amount);
        //     $("#pfAmount"+j).focus();
        //     return false;
        // } else if(esiAmount == "" || esiAmount == 0) {
        //     alert(err_esi_amount);
        //     $("#esiAmount"+j).focus();
        //     return false;
        } else {
            var netSalary = parseInt(basicSalary) + parseInt(insentive); // + parseInt(pfAmount) + parseInt(esiAmount);
            $("#netSalary"+j).val(netSalary);
        }
    }
    var mainmenu = $("#mainmenu").val();
    if (confirm(window.lang.salary.popup.register.text)) {
        for( i = 1; i <= count ; i++) {
            $("#netSalary"+i).attr('disabled',false);
        }
        $('#salaryAddForm').attr('action', 'addProcess?mainmenu='+mainmenu+'&time='+datetime);
        $("#salaryAddForm").submit();
    }
}

// edit salary record and redirect to edit screen
function fn_salary_edit(salaryId, mainmenu, screenName) {
    // set selected salary id
    $("#salaryId").val(salaryId);
    // default form
    let formId = "#salaryEmpForm";

    // decide form based on screen
    switch (screenName) {
        case "salary_view":
            if ($("#screenName").val() === "") {
                $("#backScreenName").val("salary_view_form_index");
            }
            formId = "#salaryViewForm";
            break;
        case "salary_detail_view":
            formId = "#salaryDetailForm";
            break;
        default:
            formId = "#salaryEmpForm";
            break;
    }
    // build action URL
    const actionUrl = "edit?mainmenu=" + mainmenu + "&time=" + datetime;
    // update form action and submit
    $(formId).attr("action", actionUrl);
    $(formId).trigger("submit");
}

// back button common function
function goto_back() {
    let formId = "#salaryEmpForm";
    let action = "index";
    let mainmenu = $("#mainmenu").val();
    let screenName = $("#screenName").val();
    // coming from salary view screen
    if (screenName === "salary_view") {
        formId = "#salaryViewForm";
        action = "index";
    }
    // coming from salary detail view screen
    if (screenName === "salary_detail_view") {
        formId = "#salaryDetailForm";
        action = "index";
    }
    // coming from payslip screen
    if (screenName === "payslip_index") {
        formId = "#salaryViewForm";
        action = "../paySlip/index";
        mainmenu = "paySlip_emp";
    }
    $("#mainmenu").val(mainmenu);
    $(formId).attr("action", action + "?mainmenu=" + mainmenu + "&time=" + datetime);
    $(formId).submit();
}

// edit salary validation and submit
function edit_salary_process() {
    // clear previous errors
    clear_validation();
    var basicSalary = $("#basicSalary").val().trim();
    var insentive   = $("#insentive").val().trim();
    var pfAmount    = $("#pfAmount").val().trim();
    var esiAmount   = $("#esiAmount").val().trim();

    var isValid = true;
    if (basicSalary == "" || basicSalary == 0) {
        show_validation(
            "basicSalary",
            "basicSalaryError",
            window.lang.salary.validation.basic_salary.required
        );
        $("#basicSalary").focus();
        isValid = false;
    }
    if (insentive == "" || insentive == 0) {
        show_validation(
            "insentive",
            "insentiveError",
            window.lang.salary.validation.insentive.required
        );
        if (isValid) {
            $("#insentive").focus();
        }
        isValid = false;
    }
    // Uncomment if PF is mandatory
    /*
        if (pfAmount == "" || pfAmount == 0) {
            show_validation(
                "pfAmount",
                "pfAmountError",
                window.lang.salary.validation.pf.required
            );
            if (isValid) {
                $("#pfAmount").focus();
            }
            isValid = false;
        }
    */
    // Uncomment if ESI is mandatory
    /*
        if (esiAmount == "" || esiAmount == 0) {
            show_validation(
                "esiAmount",
                "esiAmountError",
                window.lang.salary.validation.esi.required
            );
            if (isValid) {
                $("#esiAmount").focus();
            }
            isValid = false;
        }
    */
    if (!isValid) {
        return false;
    } else {
        var netSalary = parseInt(basicSalary) + parseInt(insentive); // + parseInt(pfAmount) + parseInt(esiAmount);
        $("#totalSalary").val(netSalary);
        $("#totalSalaryText").text(netSalary);
    }
    var mainmenu = $("#mainmenu").val();
    if (confirm(window.lang.salary.popup.update.text)) {
        $("#salaryEditForm").attr(
            "action",
            "editProcess?mainmenu=" + mainmenu + "&time=" + datetime
        );
        $("#salaryEditForm").submit();
    }
    return false;
}

// clear all validation messages on update button click time
function clear_validation() {
    $(".basicSalaryError").html("");
    $(".insentiveError").html("");
    $(".pfAmountError").html("");
    $(".esiAmountError").html("");

    $("#basicSalary").removeClass("is-invalid");
    $("#insentive").removeClass("is-invalid");
    $("#pfAmount").removeClass("is-invalid");
    $("#esiAmount").removeClass("is-invalid");
}

// show validation message
function show_validation(inputId, errorClass, message) {
    $("#" + inputId).addClass("is-invalid");
    $("." + errorClass).html(message);
}

// remove validation message for selected field while on change time.
function clear_field_validation(inputId, errorClass) {
    $("#" + inputId).removeClass("is-invalid");
    $("." + errorClass).html("");
}

// calculate total salary and update screen.
function calculate_net_salary() {
    var basicSalary = parseInt($("#basicSalary").val()) || 0;
    var insentive   = parseInt($("#insentive").val()) || 0;
    var pfAmount    = parseInt($("#pfAmount").val()) || 0;
    var esiAmount   = parseInt($("#esiAmount").val()) || 0;
    // calculate net salary
    var netSalary = basicSalary + insentive + pfAmount + esiAmount;
    // avoid negative value (optional)
    if (netSalary < 0) {
        netSalary = 0;
    }
    $("#totalSalary").val(netSalary);
    $("#totalSalaryText").text(netSalary.toLocaleString());
}

// get the data based the month and year click
function get_data_based_on_year_month_bar(selMonth, selYear,time) {
    $('#selMonth').val(selMonth);
    $('#selYear').val(selYear);
    var mainmenu = $('#mainmenu').val();
    $('#salaryEmpForm').attr('action', 'index?mainmenu='+mainmenu+'&time='+datetime);
    $("#salaryEmpForm").submit();
}
