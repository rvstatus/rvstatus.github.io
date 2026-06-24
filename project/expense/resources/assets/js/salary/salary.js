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
function isNumberKey(evt) { 
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   return true;
}

// if any value type on screen fnCancelCheck call
function fnCancelCheck() {
    cancel_check = false;
    return cancel_check;
}
// edit and add screen common method end

// register screen addAll Method used to register the data
function addAll(i,msg,count) {
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