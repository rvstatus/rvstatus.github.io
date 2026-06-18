@extends('layouts.app')
@section('content')

<?php

use App\Http\Helpers;
use Carbon\Carbon;
?>

<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/salary/list.css') }}" rel="stylesheet">

<script type="text/javascript">
  var datetime = '<?php echo date('Ymdhis'); ?>';
</script>

<!-- Session msg start -->
<div>
  @if(session()->has('response'))
  <div id="response_message" class="alert {{ session('response.design') }}" style="margin:0; padding:2px 5px;">
    {{ session('response.message') }}
  </div>
  @endif
</div>
<!-- Session msg end -->

@php
if($request->selYear=="" && $request->selMonths=="") {
$date = date('Y-m-d');
$newdate = strtotime('-1 month', strtotime($date));
$mons = date('m', $newdate);
$mnth = strtotime($date .' -1 months');
$yrs = date('Y', $mnth);
$request->selMonth = $mons;
$request->selYear = $yrs;
$previousmonth = new Carbon('first day of last month');
$prevmn = $previousmonth->format('m');
$request->selMonths = $prevmn;
}
@endphp


<div class="container">
  <div class="panel panel-default">
    <!-- HEADER -->
    <div class="panel-heading emp-panel-heading d-flex justify-content-between align-items-center">
      <h4 style="margin:0;">
        {{ trans('labels.salarydetails') }}
      </h4>
    </div>
    <div class="clearfix mb10 mt10">
      <!-- Employee Selection -->
      <div class="pull-left">
        <a href="javascript:show_emp_selection_popup();" class="btn btn-light btn-sm">
          <i class="fa fa-edit"></i>
          {{ trans('labels.employee_selection') }}
        </a>
      </div>
      <!-- Register -->
      @if(!empty($selectedUserSalaryCount) && $selectedUserSalaryCount != 0)
      <div class="pull-right mr10">
        <a href="javascript:add_salary('{{ $request->selYear}}','{{ $request->selMonth}}');" class="btn btn-success btn-sm">
          <i class="fa fa-plus"></i>
          {{ trans('labels.register') }}
        </a>
      </div>
      @endif
    </div>
    <!-- BODY -->
    <div class="panel-body">
      <!-- YEAR MONTH (UNCHANGED LOGIC) -->
      <div class="mb10">
        {{ Helpers::display_year_month($prev_yrs,$cur_year,$cur_month,$total_yrs,$curtime) }}
      </div>
      <form name="salaryEmpForm" id="salaryEmpForm" action="{{ url('Admin/salary/index?mainmenu=paySlip_salary&time='.date('YmdHis')) }}" method="POST">
        <input type="hidden" name="mainmenu" id="mainmenu" value="paySlip_salary">
        <input type="hidden" name="plimit" id="plimit" value="{{ $request->plimit }}">
        <input type="hidden" name="page" id="page" value="{{ $request->page }}">
        <input type="hidden" name="selMonth" id="selMonth" value="{{ $request->selMonth }}">
        <input type="hidden" name="selYear" id="selYear" value="{{ $request->selYear }}">
        <input type="hidden" name="salaryId" id="salaryId" value="">
        <input type="hidden" name="backScreenName" id="backScreenName" value="">
        <input type="hidden" name="empId" id="empId" value="">
        <!-- TABLE -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <colgroup>
              <col width="6%">
              <col width="10%">
              <col>
              <col width="13%">
              <col width="10%">
              <col width="8%">
              <col width="8%">
              <col width="13%">
              <col width="5%">
            </colgroup>
            <thead style="background:#f4f6f8;">
              <tr>
                <th>{{ trans('labels.sno') }}</th>
                <th>{{ trans('labels.employeno') }}</th>
                <th>{{ trans('labels.employeename') }}</th>
                <th>{{ trans('labels.basic_salary') }}</th>
                <th>{{ trans('labels.insentive') }}</th>
                <th>{{ trans('labels.PF') }}</th>
                <th>{{ trans('labels.ESI') }}</th>
                <th>{{ trans('labels.net_salary') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($employeeLists as $key => $employeeList)
              <tr>
                <td class="text-center">
                  {{ ($employeeLists->currentPage() - 1) * $employeeLists->perPage() + $key + 1 }}
                </td>
                <td class="text-center">
                  <a style="color: blue"
                    href="javascript:fn_get_detail_view('{{ $employeeList->emp_id}}','{{ $request->mainmenu }}');">
                    {{ $employeeList->emp_id}}
                  </a>
                </td>
                <td>
                  <span>{{ strtoupper($employeeList->emp_name) }}</span>
                </td>
                <td class="text-right">{{ number_format($employeeList->basicSalary) }}</td>
                <td class="text-right">{{ number_format($employeeList->insentive) }}</td>
                <td class="text-right">{{ number_format($employeeList->PF) }}</td>
                <td class="text-right">{{ number_format($employeeList->ESI) }}</td>
                <td class="text-right">{{ number_format($employeeList->netSalary) }}</td>
                <td class="text-center">
                  <?php if (!empty($employeeList->salaryId) && $employeeList->salaryId != "") { ?>
                    <a href="javascript:goto_salary_view('{{ $employeeList->emp_id}}','{{ $employeeList->salaryId }}');">
                      <img class="box20 mt3"
                        src="{{ asset('assets/images/details.png') }}"
                        title="{{ trans('labels.view') }}">
                    </a>
                  <?php } ?>
                </td>
              </tr>
              @empty
              <tr>
                <td class="text-center" colspan="9">
                  {{ trans('labels.nodatafound') }}
                </td>
              </tr>
              @endforelse
            </tbody>

          </table>
        </div>
        <div class="text-right">
          {{ $employeeLists->links() }}
        </div>
        <!-- PAGINATION (UNCHANGED EXACT LOGIC) -->
        <!-- <div class="row mt20 mb20" style="position: relative; min-height: 50px;">

          @if(!empty($employeeLists->total()))
          <div style="position: absolute; left: 15px; top: 10px;">
            <span class="text-muted" style="font-size: 13px;">
              {{ $employeeLists->firstItem() }} ~ {{ $employeeLists->lastItem() }} / {{ $employeeLists->total() }}
            </span>
          </div>
          @endif

          <div class="col-sm-12 text-center">

            @if(!empty($employeeLists->total()) && $employeeLists->lastPage() > 1)
            <div style="display:inline-block;">
              <ul class="pagination pagination-sm m0" style="display:flex;">

                <li class="{{ $employeeLists->onFirstPage() ? 'disabled' : '' }}">
                  <a href="javascript:pageClick('1')">«</a>
                </li>

                @if($employeeLists->onFirstPage())
                <li class="disabled"><span>‹</span></li>
                @else
                <li><a href="javascript:pageClick('{{ $employeeLists->currentPage() - 1 }}')">‹</a></li>
                @endif

                @foreach($employeeLists->getUrlRange(max(1, $employeeLists->currentPage() - 2),
                min($employeeLists->lastPage(), $employeeLists->currentPage() + 2)) as $pg => $url)

                <li class="{{ ($employeeLists->currentPage() == $pg) ? 'active' : '' }}">
                  <a href="javascript:pageClick('{{ $pg }}')">{{ $pg }}</a>
                </li>
                @endforeach

                @if($employeeLists->hasMorePages())
                <li><a href="javascript:pageClick('{{ $employeeLists->currentPage() + 1 }}')">›</a></li>
                @else
                <li class="disabled"><span>›</span></li>
                @endif

                <li class="{{ !$employeeLists->hasMorePages() ? 'disabled' : '' }}">
                  <a href="javascript:pageClick('{{ $employeeLists->lastPage() }}')">»</a>
                </li>

              </ul>
            </div>
            @endif

            <div class="btn-group btn-group-sm"
              style="display:inline-block; margin-left:20px;">

              @foreach([10,25,50,100] as $limit)
              <button type="button"
                class="btn btn-default {{ (request('plimit', 50) == $limit) ? 'btn-primary active' : '' }}"
                onclick="javascript:pageLimitClick('{{ $limit }}');">
                {{ $limit }}
              </button>
              @endforeach

            </div>

          </div>
        </div> -->
      </form>
    </div>
  </div>
</div>

<!-- POPUP -->
<div id="empselectionpopup" class="modal fade">
  <div id="login-overlay">
    <div class="modal-content"></div>
  </div>
</div>
<script src="{{ asset('resources/assets/js/salary/salary.js') }}"></script>

@endsection