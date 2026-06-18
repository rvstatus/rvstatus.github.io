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
function add_salary(selYear, selMonth) {
    $('#page').val('');
    $('#plimit').val(50);
    $('#selMonth').val(selMonth);
    $('#selYear').val(selYear);
    var mainmenu = $('#mainmenu').val();
    $('#salaryEmpForm').attr('action', 'addSalary?mainmenu='+mainmenu+'&time='+datetime);
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
        alert("Please Select atleast One Employee")
        return false;
    }
    var Emp_selection = "Do You Want To Add?";
    if(confirm(Emp_selection)) {
        $('#to option').prop('selected', true);
        $('#from option').prop('selected', true);
        document.empselectionform.submit();
        return true;
    } else {
        return false;
    }
}