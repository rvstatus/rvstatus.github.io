@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/salary/register.css') }}" rel="stylesheet">
<div class="container">
  <div class="panel panel-default">
    <!-- HEADER -->
    <div class="panel-heading emp-panel-heading d-flex justify-content-between align-items-center">
      <h4 style="margin:0;">
        {{ trans('labels.salary') }} ・ {{ trans('labels.register') }}
      </h4>
    </div>
    <!-- BODY -->
    <div class="panel-body">
      <!-- FORM -->
      <form name="salaryAddForm" id="salaryAddForm" method="POST" action="{{ url('salary/addProcess?mainmenu='.$request->mainmenu.'&time='.date('YmdHis')) }}">
        @csrf
        <input type="hidden" name="mainmenu" value="{{ $request->mainmenu }}">
        <input type="hidden" name="plimit" value="{{ $request->plimit }}">
        <input type="hidden" name="page" value="{{ $request->page }}">
        <input type="hidden" name="selMonth" value="{{ $request->selMonth }}">
        <input type="hidden" name="selYear" value="{{ $request->selYear }}">
        <input type="hidden" name="screenName" value="add">
        <input type="hidden" name="time" value="{{ date('YmdHis') }}">
        <input type="hidden" name="count" value="{{ count($userDetail) }}">
        <!-- YEAR / MONTH INFO (COMPACT CARD) -->
        <div class="panel panel-info salary-period-panel">
          <div class="panel-heading salary-period-heading">
            <strong>{{ trans('labels.salary') }} {{ trans('labels.period') }}</strong>
          </div>

          <div class="panel-body salary-period-body">
            <div class="row salary-period-row">

              <!-- YEAR -->
              <div class="col-sm-4 text-center salary-period-col">
                <div class="salary-period-label">
                  {{ trans('labels.year') }}
                </div>
                <div class="salary-period-value">
                  {{ $request->selYear }}
                </div>
              </div>

              <!-- MONTH -->
              <div class="col-sm-4 text-center salary-period-col">
                <div class="salary-period-label">
                  {{ trans('labels.month') }}
                </div>
                <div class="salary-period-value">
                  {{ $request->selMonth }}
                </div>
              </div>

              <!-- DAY -->
              <div class="col-sm-4 salary-period-day">
                <div class="salary-day-wrapper">
                  <label class="salary-day-label">
                    {{ trans('labels.day') }}
                  </label>

                  <select name="selDay" id="selDay" class="form-control input-sm w110" onchange="change_salary_day();" onfocus="store_old_day();">
                    @foreach($dayList as $value => $label)
                    <option value="{{ $value }}"
                      {{ ($request->selDay == $value) ? 'selected' : '' }}>
                      {{ $label }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- TABLE -->
        <div class="table-responsive">
          <table class="table table-bordered salary-table">
            <colgroup>
              <col width="5%">
              <col width="10%">
              <col>
              <col width="12%">
              <col width="12%">
              <col width="10%">
              <col width="10%">
              <col width="12%">
            </colgroup>
            <thead style="background:#f4f6f8;">
              <!-- TOP GROUP ROW -->
              <tr>
                <th rowspan="2" class="text-center cmn_vam">
                  {{ trans('labels.sno') }}
                </th>
                <th rowspan="2" class="text-center cmn_vam">
                  {{ trans('labels.employee_id') }}
                </th>
                <th rowspan="2" class="text-center cmn_vam">
                  {{ trans('labels.employee_name') }}
                </th>
                <th colspan="5" class="text-center" style="font-weight:bold;">
                  {{ trans('labels.salary_details') }}
                </th>
              </tr>
              <!-- SECOND ROW -->
              <tr>
                <th class="text-center">{{ trans('labels.basic_salary') }}</th>
                <th class="text-center">{{ trans('labels.insentive') }}</th>
                <th class="text-center">{{ trans('labels.PF') }}</th>
                <th class="text-center">{{ trans('labels.ESI') }}</th>
                <th class="text-center">{{ trans('labels.net_salary') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($userDetail as $key => $user)
              <tr>
                <td class="text-center">
                  {{ $key + 1 }}
                </td>
                <td class="text-center">
                  {{ $user->emp_id }}
                  <input type="hidden" name="empId{{ $key+1 }}" value="{{ $user->emp_id }}">
                </td>
                <td>
                  {{ $user->emp_name }}
                </td>
                <td>
                  <input type="text" name="basicSalary{{ $key+1 }}" id="basicSalary{{ $key+1 }}" class="form-control text-right input-sm salaryAdd" onkeypress="return is_number_key(event);" onchange="return fn_cancel_check();" data-key="{{ $key+1 }}">
                </td>
                <td>
                  <input type="text" name="insentive{{ $key+1 }}" id="insentive{{ $key+1 }}" class="form-control text-right input-sm salaryAdd" onkeypress="return is_number_key(event);" onchange="return fn_cancel_check();" data-key="{{ $key+1 }}">
                </td>
                <td>
                  <input type="text" name="pfAmount{{ $key+1 }}" id="pfAmount{{ $key+1 }}" class="form-control text-right input-sm salaryAdd" onkeypress="return is_number_key(event);" onchange="return fn_cancel_check();" data-key="{{ $key+1 }}">
                </td>
                <td>
                  <input type="text" name="esiAmount{{ $key+1 }}" id="esiAmount{{ $key+1 }}" class="form-control text-right input-sm salaryAdd" onkeypress="return is_number_key(event);" onchange="return fn_cancel_check();" data-key="{{ $key+1 }}">
                </td>
                <td>
                  <input type="text" name="netSalary{{ $key+1 }}" id="netSalary{{ $key+1 }}" class="form-control text-right input-sm" disabled>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center">
                  {{ trans('labels.no_data_found') }}
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- ACTION BUTTONS -->
        <div class="text-center mt20">
          <a href="javascript:cancel('index','{{$request->mainmenu}}');" class="btn btn-danger btn-sm">
            <i class="fa fa-times"></i>
            {{ trans('labels.cancel') }}
          </a>
          <button type="button" onclick="add_all('1','reg','{{ count($userDetail) }}');" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i>
            {{ trans('labels.register') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
  var datetime = '<?php echo date('Ymdhis'); ?>';
</script>
<script src="{{ asset('resources/assets/js/salary/salary.js') }}"></script>
@endsection