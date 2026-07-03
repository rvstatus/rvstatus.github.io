@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/salary/edit.css') }}" rel="stylesheet">

<script type="text/javascript">
  var datetime = '<?php echo date('Ymdhis'); ?>';
</script>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-1">
      <div class="panel panel-default salary-edit-panel">
        <div class="panel-heading">
          {{ trans('labels.salary') }}{{ trans('labels.edit') }}
        </div>
        <div class="panel-body">
          <form name="salaryEditForm" id="salaryEditForm" class="form-horizontal" method="POST" action="{{ url('salary/editProcess?time='.date('YmdHis')) }}">
            @csrf
            <input type="hidden" name="salaryId" id="salaryId" value="{{ $request->salaryId }}">
            <input type="hidden" name="empId" id="empId" value="{{ $request->empId }}">
            <input type="hidden" name="selMonth" id="selMonth" value="{{ $userSalaryEditDetail->month }}">
            <input type="hidden" name="selYear" id="selYear" value="{{ $userSalaryEditDetail->year }}">
            <input type="hidden" name="plimit" id="plimit" value="{{ $request->plimit }}">
            <input type="hidden" name="page" id="page" value="{{ $request->page }}">
            <input type="hidden" name="mainmenu" id="mainmenu" value="{{ $request->mainmenu }}">
            <input type="hidden" name="totalSalary" id="totalSalary" value="{{ $userSalaryEditDetail->totalSalary }}">
            <input type="hidden" name="screenName" id="screenName" value="edit">
            <input type="hidden" name="backScreenName" id="backScreenName" value="{{ $request->backScreenName }}">
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3"> {{ trans('labels.employee_name') }} </label>
              <div class="col-md-5">
                {{ $userSalaryEditDetail->emp_name }}
              </div>
              <div class="col-md-4"></div>
            </div>
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3"> {{ trans('labels.year_month') }} </label>
              <div class="col-md-5">
                {{ $userSalaryEditDetail->year }} / {{ $userSalaryEditDetail->month }}
              </div>
              <div class="col-md-4"></div>
            </div>
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3">
                {{ trans('labels.basic_salary') }}
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-md-5">
                <input type="text" name="basicSalary" id="basicSalary"
                  class="form-control text-right w180"
                  onkeypress="return is_number_key(event)"
                  onchange="fn_cancel_check(); calculate_net_salary(); clear_field_validation('basicSalary','basicSalaryError');"
                  value="{{ old('basicSalary', $userSalaryEditDetail->basicSalary) }}">
              </div>
              <div class="col-md-4">
                <span class="basicSalaryError text-danger"></span>
              </div>
            </div>
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3">
                {{ trans('labels.insentive') }}
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-md-5">
                <input type="text" name="insentive" id="insentive"
                  class="form-control text-right w180"
                  onkeypress="return is_number_key(event)"
                  onchange="fn_cancel_check(); calculate_net_salary(); clear_field_validation('insentive','insentiveError');"
                  value="{{ old('insentive', $userSalaryEditDetail->insentive) }}">
              </div>
              <div class="col-md-4">
                <span class="insentiveError text-danger"></span>
              </div>
            </div>
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3">
                {{ trans('labels.PF') }}
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-md-5">
                <input type="text" name="pfAmount" id="pfAmount"
                  class="form-control text-right w180"
                  onkeypress="return is_number_key(event)"
                  onchange="fn_cancel_check(); calculate_net_salary(); clear_field_validation('pfAmount','pfAmountError');"
                  value="{{ old('pfAmount', $userSalaryEditDetail->pfAmount) }}">
              </div>
              <div class="col-md-4">
                <span class="pfAmountError text-danger"></span>
              </div>
            </div>
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3">
                {{ trans('labels.ESI') }}
                <!-- <span class="text-danger">*</span> -->
              </label>
              <div class="col-md-5">
                <input type="text" name="esiAmount" id="esiAmount"
                  class="form-control text-right w180"
                  onkeypress="return is_number_key(event)"
                  onchange="fn_cancel_check(); calculate_net_salary(); clear_field_validation('esiAmount','esiAmountError');"
                  value="{{ old('esiAmount', $userSalaryEditDetail->esiAmount) }}">
              </div>
              <div class="col-md-4">
                <span class="esiAmountError text-danger"></span>
              </div>
            </div>
            <div class="form-group salary-form-group">
              <label class="control-label col-md-3">
                {{ trans('labels.total') }}
              </label>
              <div class="col-md-5">
                <p>
                  <b id="totalSalaryText">
                    {{
                      number_format(
                        $userSalaryEditDetail->basicSalary +
                        $userSalaryEditDetail->insentive -
                        $userSalaryEditDetail->pfAmount -
                        $userSalaryEditDetail->esiAmount
                      ) 
                    }}
                  </b>
                </p>
              </div>
              <div class="col-md-4"></div>
            </div>
            <div class="form-group salary-form-group">
              <div class="col-md-6 col-md-offset-3 btn-group-responsive">
                <button type="button"
                  class="btn btn-warning"
                  onclick="return edit_salary_process();">
                  <i class="fa fa-edit"></i>
                  {{ trans('labels.update') }}
                </button>
                <a href="javascript:cancel('index','{{$request->mainmenu}}');" class="btn btn-danger">
                  <i class="fa fa-times"></i>
                  {{ trans('labels.cancel') }}
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('resources/assets/js/salary/salary.js') }}"></script>
@endsection