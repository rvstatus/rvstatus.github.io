@extends('layouts.app')
@section('content')
<link href="{{ asset('resources/assets/css/employee/detail.css') }}" rel="stylesheet">
<div class="container">
    <div class="panel panel-default">
        <!-- HEADER -->
        <div class="panel-heading emp-panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="employee-title">Employee Detail</h4>
                </div>
            </div>
        </div>
        <!-- BUTTON ROW -->
        <div class="panel-body employee-panel-body">
            <div class="row employee-action-row">
                <div class="col-md-12">
                    <a href="{{ url('/employee_list') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    @if($employee->deleted_flg == 1)
                    <!-- employee inactive -->
                    <a href="javascript:void(0)" class="btn btn-success btn-sm" id="btn_delete_revert" data-action="{{ url('/employee_revert') }}">
                        <i class="fa fa-undo"></i> Revert
                    </a>
                    @else
                    <!-- employee active -->
                    <a href="javascript:void(0)" class="btn btn-warning btn-sm" id="btn_edit" data-action="{{ url('/employee_edit') }}">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="javascript:void(0)" class="btn btn-danger btn-sm" id="btn_delete" data-action="{{ url('/employee_delete') }}">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                    @endif
                    <!-- edit, delete and revert form -->
                    <form id="employee_form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="employee_id" value="{{ $employee->id }}">
                    </form>
                </div>
            </div>
            @if(session()->has('response'))
            <div class="alert {{ session('response.design') }}">
                {{ session('response.message') }}
            </div>
            @endif
            <!-- PERSONAL DETAILS -->
            <fieldset class="employee-fieldset">
                <legend>Personal Details</legend>
                <table class="table table-bordered detail-table">
                    <tr>
                        <th>Employee Name</th>
                        <td data-label="Employee Name">{{ $employee->emp_name }}</td>
                        <th>Employee ID</th>
                        <td data-label="Employee ID">{{ $employee->emp_id }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td data-label="Gender">
                            @if($employee->gender == 1)
                            Male
                            @elseif($employee->gender == 2)
                            Female
                            @else
                            -
                            @endif
                        </td>
                        <th>Mobile Number</th>
                        <td data-label="Mobile Number">{{ $employee->mobile_no }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td data-label="Email">{{ $employee->email }}</td>
                        <th>Date OF Birth</th>
                        <td data-label="Date OF Birth">{{ $employee->date_of_birth }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td data-label="Address">{!! nl2br(e($employee->address)) !!}</td>
                    </tr>
                </table>
            </fieldset>
            <!-- WORK DETAILS -->
            <fieldset class="employee-fieldset">
                <legend>Work Details</legend>
                <table class="table table-bordered detail-table">
                    <tr>
                        <th>Category</th>
                        <td data-label="Category">{{ $employee->work_category_name ?? '-' }}</td>
                        <th>Salary</th>
                        <td data-label="Salary">
                            {{ $employee->salary ? '₹ '.number_format($employee->salary,0,'.',',') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Join Date</th>
                        <td data-label="Join Date">
                            {{ !empty($employee->join_date) ? date('d-m-Y', strtotime($employee->join_date)) : '-' }}
                        </td>
                        <th>Leave Date</th>
                        <td data-label="Leave Date">
                            {{ !empty($employee->leave_date) ? date('d-m-Y', strtotime($employee->leave_date)) : 'Nil' }}
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
</div>
<script src="{{ asset('resources/assets/js/employee/detail.js') }}"></script>
@endsection