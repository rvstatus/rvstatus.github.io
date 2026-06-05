@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/width.css') }}" rel="stylesheet">
<link href="{{ asset('resources/assets/css/employee/list.css') }}" rel="stylesheet">
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading emp-panel-heading d-flex justify-content-between align-items-center">
            <h4 style="margin:0;">
                Employee List
            </h4>
            <div>
                @if(session()->has('response'))
                <div id="response_message" class="alert {{ session('response.design') }}" style="margin:0; padding:2px 5px;">
                    {{ session('response.message') }}
                </div>
                @endif
            </div>
            <div>
                <a href="{{ url('/employee_register') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Register
                </a>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col>
                        <col width="7%">
                        <col width="12%">
                        <col width="9%">
                        <col width="11%">
                        <col width="11%">
                        <col width="11%">
                    </colgroup>
                    <thead style="background:#f4f6f8;">
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Salary</th>
                            <th class="text-center">Join Date</th>
                            <th class="text-center">Leave Date</th>
                            <th class="text-center">Mobile</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($employee_list as $key => $employee)
                        <tr>
                            <td class="text-center">
                                {{ ($employee_list->currentPage() - 1) * $employee_list->perPage() + $key + 1 }}
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" onclick="empDetailView('{{ $employee->id }}')" class="emp-code-link">
                                    {{ $employee->emp_id }}
                                </a>
                            </td>
                            <td>{{ $employee->emp_name }}</td>
                            <td>
                                @if($employee->gender == 1) Male
                                @elseif($employee->gender == 2) Female
                                @else -
                                @endif
                            </td>
                            <td>{{ $employee->work_category_name ?? '-' }}</td>
                            <td class="text-right">{{ $employee->salary ? '₹ ' . number_format($employee->salary, 0, '.', ',') : '-' }}</td>
                            <td class="text-center"> {{ !empty($employee->join_date) ? date('d-m-Y', strtotime($employee->join_date)) : '-' }} </td>
                            <td class="text-center"> {{ !empty($employee->leave_date) ? date('d-m-Y', strtotime($employee->leave_date)) : 'Nil' }} </td>
                            <td class="text-center">{{ $employee->mobile_no ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                {{ __('messages.employee.list.empty') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="text-right">
                    {{ $employee_list->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('resources/assets/js/employee/list.js') }}"></script>
@endsection