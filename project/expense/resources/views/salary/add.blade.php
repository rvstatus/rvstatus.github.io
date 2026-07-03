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
      <!-- YEAR / MONTH INFO (COMPACT CARD) -->
      <div class="panel panel-info" style="margin-bottom:10px;">
        <div class="panel-heading" style="padding:5px 12px;">
          <strong style="font-size:13px;">
            {{ trans('labels.salary') }} {{ trans('labels.period') }}
          </strong>
        </div>
        <div class="panel-body" style="padding:8px 10px;">
          <div class="row" style="margin:0;">
            <!-- YEAR -->
            <div class="col-sm-6 text-center" style="border-right:1px solid #e5e5e5; padding:5px 0;">
              <div style="font-size:11px; color:#888;">
                {{ trans('labels.year') }}
              </div>
              <div style="font-size:16px; font-weight:600; color:#2c3e50; line-height:18px;">
                {{ $request->selYear }}
              </div>
            </div>
            <!-- MONTH -->
            <div class="col-sm-6 text-center" style="padding:5px 0;">
              <div style="font-size:11px; color:#888;">
                {{ trans('labels.month') }}
              </div>
              <div style="font-size:16px; font-weight:600; color:#2c3e50; line-height:18px;">
                {{ $request->selMonth }}
              </div>
            </div>
          </div>
        </div>
      </div>
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