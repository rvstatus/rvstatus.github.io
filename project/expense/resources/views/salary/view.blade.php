@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/salary/view.css') }}" rel="stylesheet">
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default salary-view-panel">
        <div class="panel-heading">
          <i class="fa fa-money"></i>
          {{ trans('labels.salary') }} {{ trans('labels.view') }}
        </div>
        <div class="panel-body">
          <!-- Session msg start -->
          <div>
            @if(session()->has('response'))
            <div id="response_message" class="alert {{ session('response.design') }}" style="padding:8px 12px;margin-bottom:10px;">
              {{ session('response.message') }}
            </div>
            @endif
          </div>
          <!-- Session msg end -->
          <form id="salaryViewForm" name="salaryViewForm" method="POST" action="{{ url('salary/index?time='.date('YmdHis')) }}">
            @csrf
            <input type="hidden" name="mainmenu" id="mainmenu" value="{{ $request->mainmenu ?? 'paySlip_salary' }}">
            <input type="hidden" name="plimit" id="plimit" value="{{ $request->plimit }}">
            <input type="hidden" name="page" id="page" value="{{ $request->page }}">
            <input type="hidden" name="selMonth" id="selMonth" value="{{ $request->selMonth }}">
            <input type="hidden" name="selYear" id="selYear" value="{{ $request->selYear }}">
            <input type="hidden" name="empId" id="empId" value="{{ $request->empId }}">
            <input type="hidden" name="salaryId" id="salaryId" value="{{ $request->salaryId }}">
            <input type="hidden" name="screenName" id="screenName" value="salary_view">
            <input type="hidden" name="backScreenName" id="backScreenName" value="salary_view">
            <!-- Buttons -->
            <div class="form-group">
              <div class="col-md-12 text-left">
                <button type="button" onclick="goto_back();" class="btn btn-primary">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('labels.back') }}
                </button>
                @if(empty($request->mailSendStatus))
                <button type="button" class="btn btn-warning" onclick="fn_salary_edit('{{ $salaryViewDetail->salaryId }}', '{{ $request->mainmenu }}',              'salary_view'          );">
                  <i class="fa fa-edit"></i>
                  {{ trans('labels.edit') }}
                </button>
                @endif
              </div>
            </div>
            <!-- Employee Information -->
            <fieldset class="salary-fieldset">
              <legend>{{ trans('labels.employee_information') }}</legend>
              <div class="salary-info-box">
                <div class="salary-row">
                  <span class="label-text">
                    {{ trans('labels.employee_id') }}
                  </span>
                  <span class="value-text">
                    {{ $salaryViewDetail->emp_id }}
                  </span>
                </div>
                <div class="salary-row">
                  <span class="label-text">
                    {{ trans('labels.employee_name') }}
                  </span>
                  <span class="value-text employee-name">
                    {{ strtoupper($salaryViewDetail->emp_name) }}
                  </span>
                </div>
                <div class="salary-row">
                  <span class="label-text">
                    {{ trans('labels.year_month') }}
                  </span>
                  <span class="value-text">
                    {{ $salaryViewDetail->year }}/{{ str_pad($salaryViewDetail->month,2,'0',STR_PAD_LEFT) }}
                  </span>
                </div>
              </div>
            </fieldset>

            <!-- Salary Details -->
            <fieldset class="salary-fieldset">
              <legend>{{ trans('labels.salary_details') }}</legend>
              <div class="salary-summary">
                <div class="salary-row">
                  <span>{{ trans('labels.basic_salary') }}</span>
                  <span>₹ {{ number_format($salaryViewDetail->basicSalary) }}</span>
                </div>
                <div class="salary-row">
                  <span>{{ trans('labels.insentive') }}</span>
                  <span class="text-success">
                    + ₹ {{ number_format($salaryViewDetail->insentive) }}
                  </span>
                </div>
                <div class="salary-row">
                  <span>{{ trans('labels.PF') }}</span>
                  <span class="text-danger">
                    ₹ {{ number_format($salaryViewDetail->pfAmount) }}
                  </span>
                </div>
                <div class="salary-row">
                  <span>{{ trans('labels.ESI') }}</span>
                  <span class="text-danger">
                    ₹ {{ number_format($salaryViewDetail->esiAmount) }}
                  </span>
                </div>
                <div class="salary-row net-salary">
                  <span>{{ trans('labels.net_salary') }}</span>
                  <span>₹ {{ number_format($salaryViewDetail->netSalary) }}</span>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('resources/assets/js/salary/salary.js') }}"></script>
<script>
  var datetime = '{{ date("YmdHis") }}';
</script>
@endsection