@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/salary/detail_view.css') }}" rel="stylesheet">
<form name="salaryDetailForm" id="salaryDetailForm" method="POST" action="{{ url('salary/detailView?mainmenu='.$request->mainmenu.'&time='.date('YmdHis')) }}">
  <div class="container">
    <div class="panel panel-default">
      @csrf
      <input type="hidden" name="mainmenu" id="mainmenu" value="{{ $request->mainmenu }}">
      <input type="hidden" name="plimit" id="plimit" value="{{ $request->plimit }}">
      <input type="hidden" name="page" id="page" value="{{ $request->page }}">
      <input type="hidden" name="selMonth" id="selMonth" value="{{ $request->selMonth }}">
      <input type="hidden" name="selYear" id="selYear" value="{{ $request->selYear }}">
      <input type="hidden" name="screenName" id="screenName" value="salary_detail_view">
      <input type="hidden" name="empId" id="empId" value="{{ $request->empId }}">
      <input type="hidden" name="salaryId" id="salaryId" value="">
      <input type="hidden" name="yearViseData" id="yearViseData" value="{{ $request->year }}">
      <input type="hidden" name="backScreenName" id="backScreenName" value="salary_detail_view">
      <!-- HEADER -->
      <div class="panel-heading emp-panel-heading">
        <h4 style="margin:0;">
          {{ trans('labels.salary_details') }}
        </h4>
      </div>
      <!-- BODY -->
      <div class="panel-body">
        <div class="mb10 mt10 header-actions">
          <button type="button" onclick="goto_back();" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
            {{ trans('labels.back') }}
          </button>

          <div class="w180">
            <select name="year" id="year" class="form-control input-sm year-filter">
              <option value="">All</option>
              @foreach($salaryYearArray as $value => $label)
              <option value="{{ $value }}" {{ $request->year == $value ? 'selected' : '' }}>
                {{ $label }}
              </option>
              @endforeach
            </select>
          </div>

        </div>
        @if(!empty($salaryDetail) && isset($salaryDetail[0]))
        <div class="emp-info-box">
          <label>{{ trans('labels.employee_id') }} :</label>
          <span class="mr40">{{ $salaryDetail[0]->emp_id }}</span>

          <label>{{ trans('labels.employee_name') }} :</label>
          <span class="emp-name">{{ $salaryDetail[0]->emp_name }}</span>
        </div>
        @endif
        <div class="clearfix"></div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover salary-table">
            <colgroup>
              <col width="5%">
              <col width="11%">
              <col>
              <col>
              <col width="12%">
              <col width="12%">
              <col>
              <col>
              <col width="6%">
            </colgroup>
            <thead>
              <tr>
                <th>{{ trans('labels.sno') }}</th>
                <th>{{ trans('labels.date') }}</th>
                <th>{{ trans('labels.basic_salary') }}</th>
                <th>{{ trans('labels.insentive') }}</th>
                <th>{{ trans('labels.PF') }}</th>
                <th>{{ trans('labels.ESI') }}</th>
                <th>{{ trans('labels.total') }}</th>
                <th>{{ trans('labels.salary_take_home') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($salaryDetail as $key => $detail)
              <tr>
                <td class="text-center cmn_vam">
                  {{ $key + 1 }}
                </td>
                <td class="text-center cmn_vam">
                  {{ \Carbon\Carbon::create($detail->year, $detail->month, $detail->day)->format('Y-m-d') }}
                </td>
                <td class="text-right cmn_vam">
                  {{ number_format($detail->basicSalary) }}
                </td>
                <td class="text-right cmn_vam">
                  {{ number_format($detail->insentive) }}
                </td>
                <td class="text-right cmn_vam">
                  {{ number_format($detail->pfAmount) }}
                </td>
                <td class="text-right cmn_vam">
                  {{ number_format($detail->esiAmount) }}
                </td>
                <td class="text-right cmn_vam">
                  {{ number_format($detail->totalSalary) }}
                </td>
                <td class="text-right cmn_vam net-salary">
                  {{ number_format($detail->netSalary) }}
                </td>
                <td class="text-center cmn_vam">
                  <a href="javascript:fn_salary_edit('{{ $detail->salaryId }}','{{ $request->mainmenu }}','salary_detail_view');"
                    title="{{ trans('labels.edit') }}">
                    <i class="fa fa-edit text-primary edit-icon"></i>
                  </a>
                </td>
              </tr>
              @if(($key + 1) == count($salaryDetail))
              @forelse($totalSalaryArray as $totalAmount)
              <tr class="salary-total-row">
                <td style="background-color:white;border-color:white;"></td>
                <td style="background-color:white;border-color:white;"></td>
                <td class="text-right">
                  {{ number_format($totalAmount->basicSalary) }}
                </td>
                <td class="text-right">
                  {{ number_format($totalAmount->insentive) }}
                </td>
                <td class="text-right">
                  {{ number_format($totalAmount->pfAmount) }}
                </td>
                <td class="text-right">
                  {{ number_format($totalAmount->esiAmount) }}
                </td>
                <td class="text-right">
                  {{ number_format($totalAmount->totalSalary) }}
                </td>
                <td class="text-right" style="color:blue;">
                  {{ number_format($totalAmount->netSalary) }}
                </td>
                <td style="background-color:white;border-color:white;"></td>
              </tr>
              @empty
              @endforelse
              @endif
              @empty
              <tr>
                <td class="text-center" colspan="9">
                  {{ trans('labels.no_data_found') }}
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if(method_exists($salaryDetail, 'links'))
        <div class="text-right">
          {{ $salaryDetail->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</form>
<script src="{{ asset('resources/assets/js/salary/salary.js') }}"></script>
<script type="text/javascript">
  var datetime = "{{ date('YmdHis') }}";
</script>
@endsection