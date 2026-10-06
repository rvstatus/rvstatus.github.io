<link href="{{ asset('resources/assets/css/popup/emp_selection_popup.css') }}" rel="stylesheet">
<div class="popupstyle popupsize">
  <div class="modal-content">
    <!-- HEADER -->
    <div class="modal-header popH_color">
      <button type="button" onclick="div_popup_close();" class="close">
        <i class="glyphicon glyphicon-remove"></i>
      </button>
      <h3>
        <b>{{ trans('labels.employee_selection') }}</b>
      </h3>
    </div>
    <form name="empselectionform" id="empselectionform" action="{{ url('salary/empselectionprocess') }}" method="POST">
      @csrf
      <input type="hidden" name="mainmenu" id="mainmenu" value="{{ $request->mainmenu }}">
      <input type="hidden" name="year" id="year" value="{{ $request->year }}">
      <input type="hidden" name="month" id="month" value="{{ $request->month }}">

      @php
      $days = cal_days_in_month( CAL_GREGORIAN, $request->month, $request->year );
      @endphp
      <!-- BODY -->
      <div class="modal-body">
        <div class="salary-period-box">
          <div class="row salary-row" style="justify-content: center !important;">
            <div class="col-md-3 col-sm-2">
              <label class="salary-label">{{ trans('labels.year') }}</label>
              <div class="salary-value">{{ $request->year }}</div>
            </div>
            <div class="col-md-4 col-sm-2">
              <label class="salary-label">{{ trans('labels.month') }}</label>
              <div class="salary-value">
                {{ date('F', mktime(0,0,0,$request->month,1)) }}
              </div>
            </div>
            <div class="col-md-5 col-sm-2">
              <label class="salary-label">{{ trans('labels.day') }}</label>
              <select name="day" id="day" class="form-control salary-select" onchange="on_change_salary_day_select_box(this.value)" onfocus="store_old_day();">
                @foreach($dayList as $value => $label)
                <option value="{{ $value }}" {{ $request->day == $value ? 'selected' : '' }}>
                  {{ $label }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <hr class="salary-divider">
        <div class="employee-selection-wrapper">
          <!-- UNSELECTED -->
          <div class="employee-list">
            <div class="list-title-unselected">
              <i class="fa fa-users"></i>
              {{ trans('labels.un_selected') }}
            </div>
            <select multiple id="from" name="removed[]" class="employee-select">
              @foreach($employeeUnselect as $employeesdeselect)
              <option value="{{ $employeesdeselect->emp_id }}">
                {{ $employeesdeselect->emp_name }}
              </option>
              @endforeach
            </select>
          </div>
          <!-- ARROWS -->
          <div class="controls">
            <a href="javascript:move_selected('from','to')">
              <i class="fa fa-angle-right"></i>
            </a>
            <a href="javascript:move_selected('to','from')">
              <i class="fa fa-angle-left"></i>
            </a>
          </div>
          <!-- SELECTED -->
          <div class="employee-list">
            <div class="list-title-selected">
              <i class="fa fa-check-circle"></i>
              {{ trans('labels.selected') }}
            </div>
            <select multiple id="to" name="selected[]" class="employee-select">
              @foreach($employeeSelect as $employeesselected)
              <option value="{{ $employeesselected->emp_id }}">
                {{ $employeesselected->emp_name }}
              </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <!-- FOOTER -->
      <div class="modal-footer popF_color">
        <div class="footer-buttons">
          <button id="add" type="submit" onclick="return emp_select_by_popup_click();" class="btn btn-success">
            <i class="fa fa-plus"></i>
            {{ trans('labels.add') }}
          </button>
          <button type="button" onclick="div_popup_close();" class="btn btn-danger">
            <i class="glyphicon glyphicon-remove"></i>
            {{ trans('labels.cancel') }}
          </button>
        </div>
      </div>
    </form>
  </div>
  <script src="{{ asset('resources/assets/js/popup/emp_selection_popup.js') }}"></script>
</div>